from fastapi import FastAPI, Body
import joblib
import numpy as np
import pickle
import shap

app = FastAPI(title="HealthAI Service", version="1.0")   

heart_model = joblib.load("heart_model.pkl")
diabetes_model = joblib.load("diabetes_model.pkl")

heart_explainer = shap.TreeExplainer(heart_model)
diabetes_explainer = shap.TreeExplainer(diabetes_model)



@app.get("/", tags=["General"]) 
def read_root():
    if heart_model and diabetes_model: 
        return {"status": "HealthAI Service is running."} 

@app.post("/predict-heart", tags=["Prediction"]) 
def predict_heart(data: list = Body(...)):
    input_data = np.array(data).reshape(1,-1)
    prediction = heart_model.predict(input_data)

    shap_values = heart_explainer.shap_values(input_data)

    if isinstance(shap_values, list): 
        current_shape = shap_values[1][0].tolist()
    else:
        current_shape = shap_values[0][:, 1].tolist() if len(shap_values.shape) > 2 else shap_values[0].tolist()


    return {"risk": int(prediction[0]),
            "shap_values": current_shape}

@app.post("/predict-diabetes", tags=["Prediction"])
def predict_diabetes(data: list = Body(...)):
    input_data = np.array(data).reshape(1,-1)
    prediction = diabetes_model.predict(input_data)
    return{"risk": int(prediction[0])}