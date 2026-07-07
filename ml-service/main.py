from fastapi import FastAPI, Body
import joblib
import numpy as np
import pickle
import shap

app = FastAPI(title="HealthAI Service", version="1.0")   

heart_model = joblib.load("heart_model.pkl")
diabetes_model = joblib.load("diabetes_model.pkl")

def build_explainer(model):
    try:
        explainer = shap.TreeExplainer(model)
        print("SHAP explainer created:", type(model))
        return explainer
    except Exception as e:
        print("SHAP ERROR:", repr(e))
        return None

heart_explainer = build_explainer(heart_model)
diabetes_explainer = build_explainer(diabetes_model)


def extract_shap_values(explainer, input_data):
    if explainer is None:
        return []

    shap_values = explainer.shap_values(input_data)

    if isinstance(shap_values, list):
        return shap_values[1][0].tolist()

    return shap_values[0][:, 1].tolist() if len(shap_values.shape) > 2 else shap_values[0].tolist()



@app.get("/", tags=["General"]) 
def read_root():
    if heart_model and diabetes_model: 
        if heart_explainer and diabetes_explainer:
            return {"status":"HealthAI Service is running."}
        return {"status": "Models works, explainer problem."} 
    return {"status": "Models doesn't work."}
        

@app.post("/predict-heart", tags=["Prediction"]) 
def predict_heart(data: list = Body(...)):
    input_data = np.array(data).reshape(1,-1)
    prediction = heart_model.predict(input_data)

    current_shape = extract_shap_values(heart_explainer, input_data)


    return {"risk": int(prediction[0]),
            "shap_values": current_shape}

@app.post("/predict-diabetes", tags=["Prediction"])
def predict_diabetes(data: list = Body(...)):
    input_data = np.array(data).reshape(1,-1)
    prediction = diabetes_model.predict(input_data)

    current_shape = extract_shap_values(diabetes_explainer, input_data)


    return{"risk": int(prediction[0]),
           "shap_values" : current_shape}