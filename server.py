from flask import Flask, request, jsonify
from flask_cors import CORS  # Importando CORS
import pymysql
from rapidfuzz import process, fuzz

app = Flask(__name__)
CORS(app)  # Habilitando CORS para todas as rotas

# Configurar conexão com MySQL
db = pymysql.connect(host="localhost", user="root", password="", database="artuniverse")
cursor = db.cursor()

def buscar_posts(termo):
    cursor.execute("SELECT id, id_name, title, id_user, creation_date, type, poster, description, archive FROM allposts")
    posts = cursor.fetchall()

    titulos_descricoes = [(post[2] + " " + post[7]).lower() for post in posts]  # Converte tudo para minúsculas
    melhores_resultados = process.extract(termo.lower(), titulos_descricoes, scorer=fuzz.WRatio, limit=10)

    resultados = []
    for match in melhores_resultados:
        title_desc, score, index = match
        if score < 60:  # Aumentando o threshold para maior precisão
            continue
        post = posts[index]

        if post[8] == "true":
            continue

        cursor.execute("SELECT user_name, profile_photo FROM users WHERE id = %s", (post[3],))
        user = cursor.fetchone()
        username = user[0] if user else "Desconhecido"
        profile_photo = f"/{user[1]}" if user and user[1] else "saturn_background.jpg"

        cursor.execute("SELECT COUNT(*) FROM video_views WHERE id_video = %s", (post[0],))
        views = cursor.fetchone()[0]

        post_type = post[5]

        if post_type == "image":
            thumbnail = f"public/storage/photos/{post[1]}.jpg" if post[1] else "saturn_background.jpg"  
        elif post[6]:  
            if post_type == "video":
                thumbnail = f"public/storage/posterVideo/{post[6]}"
            elif post_type == "audio":
                thumbnail = f"public/storage/posterAudio/{post[6]}"
            else:
                thumbnail = f"public/storage/{post[6]}"
        else:
            thumbnail = ""

        resultados.append({
            "category": "post",
            "id": post[0],
            "id_name": post[1],
            "title": post[2],
            "user": username,
            "profile_photo": profile_photo,
            "created_at": post[4],
            "type": post[5],
            "thumbnail": thumbnail,
            "views": views,
            "description": post[7],
            "relevancia": score  # Adicionando relevância para debug
        })
    
    return resultados

def buscar_usuarios(termo):
    cursor.execute("SELECT id, full_name, user_name, bio_user, profile_photo FROM users")
    users = cursor.fetchall()

    nomes = [user[1] + " " + user[2] for user in users]  # Nome completo + Nome de usuário
    melhores_resultados = process.extract(termo, nomes, scorer=fuzz.WRatio, limit=10)

    resultados = []
    for match in melhores_resultados:
        nome_usuario, score, index = match
        if score < 60:  # Aumentando o threshold
            continue
        user = users[index]

        cursor.execute("SELECT COUNT(*) FROM followers WHERE followed_id = %s", (user[0],))
        followers_count = cursor.fetchone()[0]

        resultados.append({
            "category": "user",
            "id": user[0],
            "full_name": user[1],
            "user_name": user[2],
            "bio": user[3],
            "profile_photo": f"/{user[4]}" if user[4] else "saturn_background.jpg",
            "followers": followers_count,
            "relevancia": score  # Adicionando relevância para debug
        })
    
    return resultados

@app.route("/buscar", methods=["GET"])
def buscar():
    termo = request.args.get("q", "").strip()
    if not termo:
        return jsonify({"erro": "Nenhum termo fornecido"}), 400

    resultados_posts = buscar_posts(termo)
    resultados_usuarios = buscar_usuarios(termo)

    return jsonify(resultados_posts + resultados_usuarios)

if __name__ == "__main__":
    app.run(debug=True, port=5000)