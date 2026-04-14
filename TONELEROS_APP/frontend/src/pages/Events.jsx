import React, { useState } from 'react';
import { Header } from '../components/common/Header';
import EventForm from '../components/events/EventForm';
import EventsList from '../components/events/EventsList';
import styles from './Events.module.css';

export const Events = () => {
  const [refreshKey, setRefreshKey] = useState(0);
  const handleCreated = () => setRefreshKey(prev => prev + 1);

  return (
    <div className={styles.page}>
      <Header showBack={true} />
      <main className="container">
        <header className={styles.header}>
          <h1 className={styles.title}>Agenda de Eventos</h1>
          <p className={styles.subtitle}>Gestiona tus actuaciones y compromisos musicales</p>
        </header>

        <section className={styles.formSection}>
          <EventForm onCreated={handleCreated} />
        </section>

        <section className={styles.listSection}>
          <EventsList key={refreshKey} />
        </section>
      </main>
    </div>
  );
};

export default Events;
