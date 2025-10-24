import React, { useState, useEffect } from 'react';

function App() {
  const [message, setMessage] = useState('');
  const [people, setPeople] = useState([]);
  const [dbUsers, setDbUsers] = useState([]);

  useEffect(() => {
    // Primera llamada para /api/home
    fetch('http://127.0.0.1:8080/api/home')
      .then(response => response.json())
      .then(data => {
        setMessage(data.message);
        setPeople(data.people);
      })
      .catch(error => console.error('Error fetching home data:', error));

    // Segunda llamada para /api/db_test
    fetch('http://127.0.0.1:8080/api/db_test')
      .then(response => response.json())
      .then(data => {
        setDbUsers(data.users);
      })
      .catch(error => console.error('Error fetching db version:', error));
  }, []);

  return (
    <div className="App">
      <header className="App-header">
        <h1>Frontend con React</h1>
        <p>Mensaje desde el backend: <strong>{message}</strong></p>
        <p>Personas: <strong>{people.join(', ')}</strong></p>
        <h2>Usuarios desde la base de datos:</h2>
        <ul>
          {dbUsers.map(user => (
            <li key={user.id}>{user.username} - {user.password}</li>
          ))}
        </ul>
      </header>
    </div>
  );
}

export default App;