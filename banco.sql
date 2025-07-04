CREATE DATABASE IF NOT EXISTS pericia;
USE pericia;

CREATE TABLE laudo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_perito VARCHAR(75) NOT NULL,
    tipo_pericia VARCHAR(2) NOT NULL,
    local_pericia VARCHAR(100) NOT NULL,
    data DATE NOT NULL,
    status VARCHAR(2) NOT NULL,
    evidencias TEXT NOT NULL,
    observacao TEXT
);
