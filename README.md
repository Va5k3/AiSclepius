# AiSclepius

**AiSclepius** is a telemedicine web platform that lets users assess their risk of cardiovascular disease and diabetes using machine learning, and enables direct patient-doctor communication through video consultations.

The name combines *AI* and *Asclepius* (the Greek god of medicine) — a symbolic pairing of artificial intelligence and medical practice.

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Architecture](#architecture)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Running Locally](#running-locally)
- [AWS Deployment](#aws-deployment)
- [Request Flow](#request-flow)
- [License](#license)

---

## Overview

The system supports three types of users:

| Role | Capabilities |
|---|---|
| **Guest** | View the landing page and information about the supported AI models |
| **Patient** | Enter medical parameters, get an AI risk assessment (cardio/diabetes), view assessment history, join video consultations with a doctor |
| **Doctor** | View the list of registered patients, keep notes and manage therapy, run AI risk assessments, join video consultations |

At the core of the system are two separate **XGBoost** models — one for cardiovascular risk and one for diabetes risk — with every prediction accompanied by a **SHAP** (SHapley Additive exPlanations) explanation, so users get more than a binary risk label: they see which medical parameters contributed most to the result.

## Features

- Registration and login (roles: patient / doctor)
- AI cardiovascular risk assessment (13 parameters: age, sex, chest pain type, resting blood pressure, cholesterol, max heart rate, resting ECG, etc.)
- AI diabetes risk assessment (8 parameters: number of pregnancies, glucose, blood pressure, skin fold thickness, insulin, BMI, etc.)
- SHAP explanation for every prediction — a visual breakdown of each parameter's contribution
- History of all past assessments
- Video consultations between patient and doctor
- Medical notes and therapy management for doctors

## Architecture

The system is built as three independent microservices, orchestrated with Docker Compose, deployed on an auto-scaling AWS infrastructure.

```
Client → Nginx (Angular) → Laravel API → FastAPI (ML) → Laravel API → Amazon RDS → Client
```

### AWS Infrastructure

<img width="1140" height="1203" alt="diagram-export-7-7-2026-1_24_02-AM" src="https://github.com/user-attachments/assets/d2155079-4aa8-4611-a9a8-26b27e571a1f" />


| Component | Role |
|---|---|
| **Application Load Balancer** | Receives HTTP requests, distributes them across active EC2 instances, performs health checks |
| **Auto Scaling Group** | Automatically manages the number of EC2 instances (1–3), based on CPU metrics (50% threshold) |
| **Amazon EC2** | Virtual servers configured as Docker hosts for the three microservices |
| **Amazon CloudWatch** | Monitors CPU utilization, triggers the Auto Scaling Group |
| **Amazon RDS (MySQL)** | Separate, managed relational database for all persistent data |

### Microservices (Docker Compose)

| Service | Technology | Role |
|---|---|---|
| `frontend` | Nginx + Angular | User interface, reverse proxy for `/api` routes |
| `backend` | Laravel (PHP) | Authentication, business logic, database access (Eloquent ORM) |
| `ml-service` | FastAPI (Python) | XGBoost prediction + SHAP explanations |

## Tech Stack

- **Frontend:** Angular, Nginx
- **Backend:** Laravel, PHP, Eloquent ORM, MySQL
- **Machine Learning:** Python, FastAPI, XGBoost, SHAP, scikit-learn
- **Infrastructure:** Docker, Docker Compose, AWS (EC2, ALB, ASG, CloudWatch, RDS)

## Screenshots

### Home Page

<img width="601" height="294" alt="image" src="https://github.com/user-attachments/assets/46147fb5-e306-46d7-970d-ef28635b93c5" />

### Risk Assessment / AI Prediction

<img width="602" height="293" alt="image" src="https://github.com/user-attachments/assets/2dc826ea-e94e-4caa-acff-b404682b4c73" />


### SHAP Explanation

<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/ed169729-5d7b-4ee6-8d22-16062f08a7b9" />


### Assessment History

<img width="602" height="279" alt="image" src="https://github.com/user-attachments/assets/fd9253b1-7c33-479a-a3c9-97555e3773cb" />


### Video Consultation

<img width="602" height="289" alt="image" src="https://github.com/user-attachments/assets/25bed7b4-bcb2-4018-9bdd-927f0a584c9e" />


### Doctor Dashboard (Patient Notes & Therapy)

<img width="603" height="288" alt="image" src="https://github.com/user-attachments/assets/641a4c02-7f5f-4f49-81c4-5d9063cc3567" />




## Project Structure

```
aisclepius/
├── docker-compose.yml
├── aisclepius-frontend/              # Angular app + Nginx configuration
├── backend-laravel/                # Laravel API
│   ├── app/Http/Controllers/
│   ├── app/Models/
│   └── ...
├── ml-service/             # FastAPI ML service
│   ├── models/
│   │   ├── heart_model.pkl
│   │   └── diabetes_model.pkl
│   └── main.py
├── docs/
│   └── aws-architecture.png
└── README.md
```

## Running Locally

### Prerequisites

- [Docker](https://docs.docker.com/get-docker/) and Docker Compose
- Git

### Steps

```bash
# Clone the repository
git clone https://github.com/Va5k3/aisclepius.git
cd aisclepius

# Start all services
docker compose up --build
```

Once running, the application is available at:

- **Frontend:** `http://localhost` (or the port defined in `docker-compose.yml`)
- **Laravel API:** `http://localhost/api`
- **FastAPI ML service:** internal only, accessed via the Laravel service (not directly exposed)

### Stopping

```bash
docker compose down
```

## AWS Deployment

The system is designed to run within an AWS environment with the following setup:

1. The **Application Load Balancer** receives traffic on the system's DNS name and distributes it across EC2 instances.
2. The **Auto Scaling Group** maintains between 1 and 3 EC2 instances, launching a new instance whenever average CPU utilization exceeds 50%.
3. Each **EC2 instance** runs the same Docker Compose configuration (frontend, backend, ml-service).
4. **Amazon CloudWatch** tracks CPU metrics and triggers scaling actions.
5. **Amazon RDS (MySQL)** stores all data independently of the EC2 instances' lifecycle.

### Basic Deployment Steps

```bash
# On each EC2 instance (part of the AMI or user-data script)
git clone https://github.com/Va5k3/aisclepius.git
cd aisclepius
docker compose up -d --build
```

ALB, ASG, and RDS configuration is done through the AWS Console.

## Request Flow

Example flow for a single AI risk assessment:

1. The user enters medical parameters through the Angular form.
2. The request passes through Nginx to the Laravel API.
3. Laravel validates the data and forwards it to the FastAPI service.
4. FastAPI runs the XGBoost model and computes SHAP values.
5. The result is returned to Laravel, which persists it in the RDS database.
6. The response is displayed to the user, along with a visual breakdown of the SHAP explanation.




