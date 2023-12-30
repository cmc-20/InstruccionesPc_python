import speech_recognition as sr
import os
import pyttsx3
import subprocess

notepad_process = None

def reconocer_voz():
    r = sr.Recognizer()
    with sr.Microphone() as source:
        print("Hablame ahora:")
        audio = r.listen(source)

    try:
        comando = r.recognize_google(audio, language='es-ES')
        print("Creo que escuche: " + comando)
        return comando
    except sr.UnknownValueError:
        print("No entendí lo que dijiste")
        return None
       
def ejecutar_comando(comando):
    global notepad_process
    if comando:
        if "abrir bloc de notas" in comando:
            notepad_process = subprocess.Popen("notepad.exe")
            hablar("Bloc de notas se ha abierto. ¿En qué más puedo ayudarte?")

        elif "quiero cerrar bloc de notas" in comando and notepad_process is not None:
            os.system("taskkill /f /im notepad.exe")

            hablar("Bloc de notas se ha cerrado. ¿En qué más puedo ayudarte?")

        elif "eso es todo" in comando:
            hablar("De acuerdo, gracias por usar este programa. ¡Hasta luego!")
            return False
        
    else:
        print("No se reconoció ningún comando")
    return True

def hablar(texto):
    engine = pyttsx3.init()
    rate = engine.getProperty('rate')   # obtener la velocidad actual
    engine.setProperty('rate', rate-80)  # disminuir la velocidad
    engine.say(texto)
    engine.runAndWait()

continuar = True
while continuar:
    comando = reconocer_voz()
    continuar = ejecutar_comando(comando)
