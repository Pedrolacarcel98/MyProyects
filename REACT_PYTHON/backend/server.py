# ...existing code...
from flask import Flask, jsonify
from flask_cors import CORS
import psycopg2
import os

# App instance
app = Flask(__name__)
CORS(app) # Esto habilita CORS para todas las rutas

# Configuración de DB (puedes usar variables de entorno)
DB_HOST = os.getenv('DB_HOST', 'localhost')
DB_PORT = os.getenv('DB_PORT', '5432')
DB_NAME = os.getenv('DB_NAME', 'toneleros')
DB_USER = os.getenv('DB_USER', 'postgres')
DB_PASSWORD = os.getenv('DB_PASSWORD', 'pua12398')

def get_db_connection():
    """Devuelve una conexión a PostgreSQL."""
    return psycopg2.connect(
        host=DB_HOST,
        port=DB_PORT,
        dbname=DB_NAME,
        user=DB_USER,
        password=DB_PASSWORD
    )

# /api/home
@app.route("/api/home", methods=['GET'])
def return_home():
    return jsonify({
        'message': "¡Hola mundo desde Flask!",
        'people': ['Jack', 'Harry', 'Aragorn']
    })

# /api/db_test - prueba de conexión
@app.route("/api/db_test", methods=['GET'])
def db_test():
    try:
        conn = get_db_connection()
        cur = conn.cursor()
        cur.execute("SELECT * FROM usuarios;")
        users = cur.fetchall()
        users_format = [{'id': row[0], 'username': row[1], 'password': row[2]} for row in users]
        cur.close()
        conn.close()
        return jsonify({'users': users_format})
    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == "__main__":
    app.run(debug=True, port=8080)
