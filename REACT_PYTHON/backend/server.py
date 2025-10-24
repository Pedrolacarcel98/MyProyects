from flask import Flask, jsonify
from flask_cors import CORS

# App instance
app = Flask(__name__)
CORS(app) # Esto habilita CORS para todas las rutas

# /api/home
@app.route("/api/home", methods=['GET'])
def return_home():
    return jsonify({
        'message': "¡Hola mundo desde Flask!",
        'people': ['Jack', 'Harry', 'Aragorn']
    })

if __name__ == "__main__":
    app.run(debug=True, port=8080)