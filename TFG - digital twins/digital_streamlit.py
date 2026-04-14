import streamlit as st
import pandas as pd
import time
import numpy as np

# --- CONFIGURACIÓN DE LA PÁGINA ---
st.set_page_config(page_title="Digital Twin: Estación Térmica", layout="wide")
st.title("🌡️ Gemelo Digital de Control Térmico")
st.write("Simulación de activo físico con control de lazo cerrado.")

# --- INICIALIZACIÓN DEL ESTADO (Session State) ---
# Streamlit se recarga en cada interacción, por lo que usamos 'session_state'
# para mantener vivos los datos del gemelo.
if 'temp_actual' not in st.session_state:
    st.session_state.temp_actual = 20.0
    st.session_state.historial = []
    st.session_state.encendido = False

# --- BARRA LATERAL (Controles del Usuario) ---
st.sidebar.header("Panel de Control")
setpoint = st.sidebar.slider("Temperatura Objetivo (°C)", 10.0, 50.0, 25.0)
st.sidebar.info(f"El gemelo intentará mantener la temperatura en {setpoint}°C")

# --- LÓGICA DEL GEMELO DIGITAL (El "Cerebro") ---
def simular_paso():
    # 1. Física: Pérdida de calor hacia el ambiente (18°C)
    st.session_state.temp_actual += (18.0 - st.session_state.temp_actual) * 0.05
    
    # 2. IA/Lógica de Control: Histeresis
    if st.session_state.temp_actual < setpoint - 0.5:
        st.session_state.encendido = True
    elif st.session_state.temp_actual > setpoint + 0.5:
        st.session_state.encendido = False
        
    # 3. Actuación: Si está encendido, calienta
    if st.session_state.encendido:
        st.session_state.temp_actual += 0.4
        
    # Guardar en historial para la gráfica
    st.session_state.historial.append(st.session_state.temp_actual)
    if len(st.session_state.historial) > 50: # Mantener solo los últimos 50 datos
        st.session_state.historial.pop(0)

# --- INTERFAZ VISUAL (Layout) ---
col1, col2 = st.columns([1, 2])

with col1:
    st.metric(label="Temperatura Actual", value=f"{st.session_state.temp_actual:.2f} °C", 
              delta=f"{st.session_state.temp_actual - setpoint:.2f} Δ")
    
    estado = "🔥 CALENTANDO" if st.session_state.encendido else "❄️ ENFRIANDO"
    st.subheader(f"Estado: {estado}")

with col2:
    # Dibujar la gráfica del gemelo
    if st.session_state.historial:
        df_hist = pd.DataFrame(st.session_state.historial, columns=["Temperatura"])
        st.line_chart(df_hist)

# --- BUCLE DE EJECUCIÓN ---
# Esto hace que la simulación corra sola
time.sleep(0.5)
simular_paso()
st.rerun()