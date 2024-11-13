import fitz  # PyMuPDF
from flask import Flask, request, jsonify
from sklearn.metrics.pairwise import cosine_similarity
import re
import string
from nltk.tokenize import word_tokenize
from nltk.corpus import stopwords
import logging
import os
from bs4 import BeautifulSoup
import torch
from transformers import BertTokenizer, BertModel
import nltk

app = Flask(__name__)
logging.basicConfig(level=logging.DEBUG)

# BERT modelini ve tokenizer'ı yükleyelim
tokenizer = BertTokenizer.from_pretrained('bert-base-multilingual-cased')
model = BertModel.from_pretrained('bert-base-multilingual-cased')

# Türkçe stop kelimelerini yükleyelim
try:
    stop_words = set(stopwords.words('turkish'))
except:
    nltk.download('stopwords')
    stop_words = set(stopwords.words('turkish'))

def clean_text(text):
    text = re.sub(r'http\S+', ' ', text)
    text = re.sub(r'RT|cc', ' ', text)
    text = re.sub(r'#\S+', '', text)
    text = re.sub(r'@\S+', '  ', text)
    text = re.sub('[%s]' % re.escape("""!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~"""), ' ', text)
    text = re.sub(r'[^\x00-\x7f]', r' ', text)  # Bu satırda Türkçe karakterler siliniyordu, bu satırı düzelttim.
    text = re.sub('\s+', ' ', text).strip()
    return text

def clean_html(raw_html):
    soup = BeautifulSoup(raw_html, "html.parser")
    return soup.get_text()

def preprocess_text(text):
    text = clean_html(text)
    text = clean_text(text)
    tokens = word_tokenize(text)
    tokens = [w.lower() for w in tokens]
    tokens = [w for w in tokens if w.isalpha()]
    tokens = [w for w in tokens if not w in stop_words]
    return " ".join(tokens)

def get_embeddings(text):
    inputs = tokenizer(text, return_tensors='pt', truncation=True, padding=True, max_length=512)
    outputs = model(**inputs)
    embeddings = outputs.last_hidden_state.mean(dim=1).detach().numpy()
    return embeddings

def calculate_similarity(resume_text, job_text):
    resume_embedding = get_embeddings(resume_text)
    job_embedding = get_embeddings(job_text)
    similarity = cosine_similarity(resume_embedding, job_embedding)
    return similarity[0][0] * 100

def Preprocessfile(filename):
    try:
        doc = fitz.open(filename)
        text = ""
        for page in doc:
            text += page.get_text()
        text = text.replace("\\n", " ")
        preprocessed_text = preprocess_text(text)
        print(f"Özgeçmiş Metni: {preprocessed_text}")  # Temizlenmiş özgeçmiş metnini ekrana yazdırır
        return preprocessed_text
    except Exception as e:
        app.logger.error("Dosya işlenirken hata oluştu: %s", e)
        raise

@app.route('/calculate_similarity', methods=['POST'])
def calculate_similarity_endpoint():
    try:
        data = request.json
        resume_path = data['resume_path']
        job_description = data['job_description']

        if not os.path.exists(resume_path):
            raise FileNotFoundError(f"Dosya bulunamadı: {resume_path}")

        resume_text = Preprocessfile(resume_path)
        job_text = preprocess_text(job_description)

        similarity_score = calculate_similarity(resume_text, job_text)
        similarity_score = round(similarity_score, 2)

        similarity_score = max(similarity_score, 0)  # Puanın negatif olmaması için kontrol

        return jsonify({"similarity_score": similarity_score})
    except Exception as e:
        app.logger.error("Benzerlik hesaplanırken hata oluştu: %s", e)
        return jsonify({"error": "Bir hata oluştu"}), 500

if __name__ == '__main__':
    app.run(debug=True)
