import os
from langchain_community.document_loaders import TextLoader
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain_community.vectorstores import SKLearnVectorStore
from langchain_huggingface.embeddings import HuggingFaceEmbeddings
from langchain_ollama import ChatOllama
from langchain.prompts import PromptTemplate
from langchain_core.output_parsers import StrOutputParser

class RAGApplication:
    def __init__(self, retriever, rag_chain):
        self.retriever = retriever
        self.rag_chain = rag_chain
    def run(self, question):
        # Retrieve relevant documents
        documents = self.retriever.invoke(question)
        # Extract content from retrieved documents
        doc_texts = "\\n".join([doc.page_content for doc in documents])
        # Get the answer from the language model
        answer = self.rag_chain.invoke({"question": question, "documents": doc_texts})
        return answer


# List of URLs to load documents from
docs_directory = "Moodle_doc_404_en"

htmlfiles = [os.path.join(root, name)
             for root, dirs, files in os.walk(docs_directory)
             for name in files
             if name.endswith((".html", ".htm"))]

docs = [TextLoader(file, encoding="utf-8").load() for file in htmlfiles]

# Load documents from the URLs
docs_list = [item for sublist in docs for item in sublist]
print(f"loaded documents {len(docs_list)}")
# Initialize a text splitter with specified chunk size and overlap
text_splitter = RecursiveCharacterTextSplitter.from_tiktoken_encoder(
    chunk_size=250, chunk_overlap=0
)
# Split the documents into chunks
doc_splits = text_splitter.split_documents(docs_list)
print(f"split the documents")
# Create embeddings for documents and store them in a vector store
vectorstore = SKLearnVectorStore.from_documents(
    documents=doc_splits,
    embedding=HuggingFaceEmbeddings(model_name="sentence-transformers/all-mpnet-base-v2"),
)
retriever = vectorstore.as_retriever(k=4)
print(f"created vecotorstore")
# Define the prompt template for the LLM
prompt = PromptTemplate(
    template="""You are an assistant for question-answering tasks.
    Use the following documents to answer the question.
    If you don't know the answer, just say that you don't know.
    Use three sentences maximum and keep the answer concise:
    Question: {question}
    Documents: {documents}
    Answer:
    """,
    input_variables=["question", "documents"],
)

llm = ChatOllama(
    model="llama3.1",
    temperature=0,
)

# Create a chain combining the prompt template and LLM
rag_chain = prompt | llm | StrOutputParser()


# Initialize the RAG application
print("starting prompt")
rag_application = RAGApplication(retriever, rag_chain)
# Example usage
question = "How do I add a course in Moodle ?"
answer = rag_application.run(question)
print("Question:", question)
print("Answer:", answer)
