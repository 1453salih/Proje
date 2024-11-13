import sys
import re
import textract
from itertools import chain
from nltk.tokenize import word_tokenize
from nltk.corpus import stopwords
import string
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.metrics.pairwise import cosine_similarity

def cleanResume(resumeText):
    resumeText = re.sub('http\S+\s*', ' ', resumeText)
    resumeText = re.sub('RT|cc', ' ', resumeText)
    resumeText = re.sub('#\S+', '', resumeText)
    resumeText = re.sub('@\S+', '  ', resumeText)
    resumeText = re.sub('[%s]' % re.escape("""!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~"""), ' ', resumeText)
    resumeText = re.sub(r'[^\x00-\x7f]', r' ', resumeText)
    resumeText = re.sub('\s+', ' ', resumeText)
    return resumeText

def Preprocessfile(filename):
    try:
        text = textract.process(filename)
    except Exception as e:
        return f"Hata: Dosya okunamıyor. {str(e)}"
    
    text = text.decode('utf-8').replace("\\n", " ")
    x = []
    tokens = word_tokenize(text)
    tok = [w.lower() for w in tokens]
    table = str.maketrans('', '', string.punctuation)
    strpp = [w.translate(table) for w in tok]
    words = [word for word in strpp if word.isalpha()]
    stop_words = set(stopwords.words('english'))
    words = [w for w in words if not w in stop_words]
    x.append(words)
    res = " ".join(chain.from_iterable(x))
    return res

if __name__ == "__main__":
    try:
        if len(sys.argv) < 3:
            print("Hata: Eksik argümanlar. Kullanım: python3 benzerlik_hesapla.py <cv_path> <ilan_aciklama>")
            sys.exit(1)

        cv_path = sys.argv[1]
        ilan_aciklama = sys.argv[2]

        y = Preprocessfile(cv_path)
        if y.startswith("Hata:"):
            print(y)
            sys.exit(1)

        x = ilan_aciklama

        text = [y, x]

        cv = CountVectorizer()
        count_matrix = cv.fit_transform(text)
        similarity_score = cosine_similarity(count_matrix)[0][1] * 100
        similarity_score = round(similarity_score, 2)

        print(similarity_score)
    except Exception as e:
        print(f"Hata: {str(e)}")
        sys.exit(1)
