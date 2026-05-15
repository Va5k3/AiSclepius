from fastapi import FastAPI, Body
import joblib
import numpy as np


app = FastAPI(title="HealthAI Service", version="1.0")   

heart_model = joblib.load("heart_model.pkl")
diabetes_model = joblib.load("diabetes_model.pkl")

@app.get("/", tags=["General"]) 
def read_root():
    if heart_model and diabetes_model: 
        return {"status": "HealthAI Service is running."} 

@app.post("/predict-heart", tags=["Prediction"]) 
def predict_heart(data: list = Body(...)):
    input_data = np.array(data).reshape(1,-1)
    prediction = heart_model.predict(input_data)
    return {"risk": int(prediction[0])}

@app.post("/predict-diabetes", tags=["Prediction"])
def predict_diabetes(data: list = Body(...)):
    input_data = np.array(data).reshape(1,-1)
    prediction = diabetes_model.predict(input_data)
    return{"risk": int(prediction[0])}