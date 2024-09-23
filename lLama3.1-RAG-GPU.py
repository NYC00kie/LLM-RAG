import os
from os.path import exists
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
docs_directory = "moodledocs_en"
persist_path="./vectorestore-moodle-doc"
llm = ChatOllama(
    model="llama3.1",
    temperature=0,
)

if not exists(persist_path):
    print("creating vectorstore")
    htmlfiles = [os.path.join(root, name)
                 for root, dirs, files in os.walk(docs_directory)
                 for name in files
                 if name.endswith((".html", ".htm"))]
    print("got all the names")
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
        embedding=HuggingFaceEmbeddings(
            model_name="sentence-transformers/all-mpnet-base-v2",
            model_kwargs={'device': 'cuda'},
            show_progress=True,
            ),
        persist_path=persist_path,
    )

    vectorstore.persist()

else:
    print("loading vectorstore")
    vectorstore = SKLearnVectorStore(
        embedding=HuggingFaceEmbeddings(
            model_name="sentence-transformers/all-mpnet-base-v2",
            model_kwargs={'device': 'cuda'},
            show_progress=True,
            ),
        persist_path=persist_path,
    )


try:
    while True:
    
        retriever = vectorstore.as_retriever(k=4)
        print(f"created vecotorstore")
        # Define the prompt template for the LLM
        prompt = PromptTemplate(
            template="""You are a nice and friendly human assistant for question-answering tasks.
                Use the following documents to answer the question.
                If you don't know the answer, just say that you don't know.
                Use six sentences maximum and keep the answer concise.
                Dont answer any question thats not about Moodle, even if u get supplied documents.
                Be the most accurate that u can be and cite the documentation headline where u got the information from at the end of ur Response.
                Question: {question}
                Documents: {documents}
                Answer:
            """,
            input_variables=["question", "documents"],
        )

        # Create a chain combining the prompt template and LLM
        rag_chain = prompt | llm | StrOutputParser()

        # Initialize the RAG application
        print("starting prompt")
        rag_application = RAGApplication(retriever, rag_chain)
        # Example usage
        question = input("Ask ur Question.")
        answer = rag_application.run(question)
        print("Question:", question)
        print("Answer:", answer)

except KeyboardInterrupt as e:
    print("Stopping")