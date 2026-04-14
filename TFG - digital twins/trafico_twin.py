import streamlit as st
import pandas as pd
import numpy as np
import time
import random

st.set_page_config(page_title="Smart Traffic Twin", layout="wide")

# --- LÓGICA DEL MOTOR DE TRÁFICO ---
class CruceTrafico:
    def __init__(self):
        # Estado: número de coches esperando en cada carril
        if 'carriles' not in st.session_state:
            st.session_state.carriles = {"Norte": 0, "Sur": 0, "Este": 0, "Oeste": 0}
            st.session_state.semaforo = "NS" # 'NS' (Norte-Sur) o 'EO' (Este-Oeste)
            st.session_state.historial_espera = []

    def simular_llegada_coches(self):
        # Llegan coches de forma estocástica (azar)
        for carril in st.session_state.carriles:
            if random.random() > 0.7: # 30% de probabilidad de que llegue un coche
                st.session_state.carriles[carril] += 1

    def flujo_trafico(self):
        # Si el semáforo está en verde para un carril, los coches pasan
        if st.session_state.semaforo == "NS":
            st.session_state.carriles["Norte"] = max(0, st.session_state.carriles["Norte"] - 2)
            st.session_state.carriles["Sur"] = max(0, st.session_state.carriles["Sur"] - 2)
        else:
            st.session_state.carriles["Este"] = max(0, st.session_state.carriles["Este"] - 2)
            st.session_state.carriles["Oeste"] = max(0, st.session_state.carriles["Oeste"] - 2)

# --- INTERFAZ STREAMLIT ---
st.title("🚦 Gemelo Digital: Cruce de Tráfico Inteligente")
cruce = CruceTrafico()

# Sidebar: Configuración de la IA
st.sidebar.header("Configuración IA")
modo_ia = st.sidebar.toggle("Activar Control Inteligente", value=False)
velocidad = st.sidebar.slider("Velocidad Simulación", 0.1, 2.0, 0.5)

# Lógica de Control (El "Gemelo" decide)
if modo_ia:
    # IA Simple: Cambia al carril que tenga más de 5 coches acumulados
    cola_ns = st.session_state.carriles["Norte"] + st.session_state.carriles["Sur"]
    cola_eo = st.session_state.carriles["Este"] + st.session_state.carriles["Oeste"]
    
    if cola_eo > cola_ns and st.session_state.semaforo == "NS":
        st.session_state.semaforo = "EO"
    elif cola_ns > cola_eo and st.session_state.semaforo == "EO":
        st.session_state.semaforo = "NS"
else:
    # Modo manual / Secuencial
    if st.button("Cambiar Semáforo Manualmente"):
        st.session_state.semaforo = "EO" if st.session_state.semaforo == "NS" else "NS"

# --- VISUALIZACIÓN ---
col1, col2, col3 = st.columns([1, 2, 1])

with col2:
    st.subheader("Estado de los Carriles")
    # Creamos un "mapa" visual usando columnas
    c1, c2, c3 = st.columns(3)
    c2.metric("NORTE ⬇️", st.session_state.carriles["Norte"])
    
    m1, m2, m3 = st.columns(3)
    m1.metric("ESTE ➡️", st.session_state.carriles["Este"])
    # Dibujamos el semáforo central
    if st.session_state.semaforo == "NS":
        m2.error("  🟢 NS | 🔴 EO")
    else:
        m2.error("  🔴 NS | 🟢 EO")
    m3.metric("OESTE ⬅️", st.session_state.carriles["Oeste"])
    
    b1, b2, b3 = st.columns(3)
    b2.metric("SUR ⬆️", st.session_state.carriles["Sur"])

# Gráfica de rendimiento
espera_total = sum(st.session_state.carriles.values())
st.session_state.historial_espera.append(espera_total)
st.write("### Congestión Total en el Tiempo")
st.line_chart(st.session_state.historial_espera[-50:])

# Bucle
cruce.simular_llegada_coches()
cruce.flujo_trafico()
time.sleep(velocidad)
st.rerun()