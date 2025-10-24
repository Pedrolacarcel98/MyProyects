import React, { useState, useEffect } from 'react';

function App() {
  // Estado para guardar el mensaje del backend
  const [message, setMessage] = useState('');
  const [people, setPeople] = useState('');

  // useEffect para hacer la petición cuando el componente se monte
  useEffect(() => {
    fetch('http://127.0.0.1:8080/api/home') // La URL de tu API de Flask
      .then(response => response.json())
      .then(data => {
        console.log(data); // Opcional: para ver los datos en la consola del navegador
        setMessage(data.message);
        setPeople(data.people);
      })
      .catch(error => console.error('Error fetching data:', error));
  }, []); // El array vacío asegura que la petición se haga solo una vez

  return (
    <div className="App">
      <header className="App-header">
        <h1>Frontend con React</h1>
        <p>Mensaje desde el backend: <strong>{message}</strong></p>
        <p>Mensaje desde el backend: <strong>{people}</strong></p>

      </header>
    </div>
  );
}

export default App;