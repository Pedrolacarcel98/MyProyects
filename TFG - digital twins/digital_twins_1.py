import pygame
import random

# Configuración básica
WIDTH, HEIGHT = 400, 500
FPS = 60

# Colores
WHITE = (240, 240, 240)
RED   = (200, 50, 50)
BLUE  = (50, 50, 200)
BLACK = (30, 30, 30)

class EstacionTemperatura:
    def __init__(self):
        # Estado del "Activo Físico"
        self.temp_actual = 20.0
        self.temp_ambiente = 18.0
        self.setpoint = 25.0  # Temperatura deseada
        self.calentador_on = False
        
    def actualizar_fisica(self):
        # El calor se disipa hacia la temp ambiente
        self.temp_actual += (self.temp_ambiente - self.temp_actual) * 0.01
        
        # Si el calentador está encendido, sube la temperatura
        if self.calentador_on:
            self.temp_actual += 0.05
        
        # Ruido térmico aleatorio (simulando sensor real)
        self.temp_actual += random.uniform(-0.02, 0.02)

    def logica_gemelo(self):
        # Control tipo ON/OFF (Histeresis simple)
        if self.temp_actual < self.setpoint - 0.5:
            self.calentador_on = True
        elif self.temp_actual > self.setpoint + 0.5:
            self.calentador_on = False

# --- Inicialización de Pygame ---
pygame.init()
screen = pygame.display.set_mode((WIDTH, HEIGHT))
pygame.display.set_caption("Gemelo Digital: Estación Térmica")
clock = pygame.time.Clock()
font = pygame.font.SysFont("Arial", 18)

estacion = EstacionTemperatura()
running = True

while running:
    for event in pygame.event.get():
        if event.type == pygame.QUIT:
            running = False
        # Control manual del setpoint
        if event.type == pygame.KEYDOWN:
            if event.key == pygame.K_UP: estacion.setpoint += 1
            if event.key == pygame.K_DOWN: estacion.setpoint -= 1

    # 1. Actualizar Modelos
    estacion.actualizar_fisica()
    estacion.logica_gemelo()

    # 2. Dibujar Gemelo Digital
    screen.fill(WHITE)
    
    # Dibujar termómetro (Visualización 2D)
    pygame.draw.rect(screen, BLACK, (180, 50, 40, 300), 2)
    alto_mercurio = min(300, max(0, estacion.temp_actual * 5))
    color_barra = RED if estacion.calentador_on else BLUE
    pygame.draw.rect(screen, color_barra, (182, 350 - alto_mercurio, 36, alto_mercurio))

    # Textos informativos
    txt_temp = font.render(f"Temp. Actual: {estacion.temp_actual:.2f} °C", True, BLACK)
    txt_set = font.render(f"Objetivo (Setpoint): {estacion.setpoint:.1f} °C", True, BLACK)
    txt_status = font.render(f"Calentador: {'ENCENDIDO' if estacion.calentador_on else 'APAGADO'}", True, color_barra)
    
    screen.blit(txt_temp, (20, 380))
    screen.blit(txt_set, (20, 410))
    screen.blit(txt_status, (20, 440))
    screen.blit(font.render("Usa Flechas ARRIBA/ABAJO para ajustar", True, (100,100,100)), (20, 470))

    pygame.display.flip()
    clock.tick(FPS)

pygame.quit()