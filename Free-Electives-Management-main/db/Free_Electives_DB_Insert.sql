INSERT INTO departments VALUES
("ADEM", "Business Administration"),
("CCOM", "Computer Science"),
("BIOL", "Biology"),
("ESPA", "Spanish"),
("ADMI", "System Administrators");

INSERT INTO users VALUES
("admin", "admin_p@ss", "Administrator", "admin", NOW(), "ADMI"),
("juano.lopez", "juano_p@ss", "Juan O. López Gerena", "chair", NOW(), "CCOM"),
("gualberto.rosado", "gualberto_p@ss", "Gualberto Rosado Rodríguez", "chair", NOW(), "BIOL"),
("jose.arbelo", "jose_p@ss", "José G. Arbelo García", "coordinator", NOW(), "BIOL"),
("eliana.valenzuela", "eliana_p@ss", "Eliana Valenzuela Andrade", "coordinator", NOW(), "CCOM"),
("rebeca.franqui", "rebeca_p@ss", "Rebeca Franqui Rosario", "chair", NOW(), "ESPA");

INSERT INTO courses VALUES
("BIOL4055", "Ciencia Ambiental", 3, "Course description for BIOL4055", "BIOL", "admin", NOW()),
("ESPA3007", "Comunicación Oral", 3, "Course description for ESPA3007", "ESPA", "admin", NOW()),
("ESPA3136", "Literatura Sacra y Religión", 3, "Course description for ESPA3136", "ESPA", "admin", NOW()),
("ESPA3211", "Introducción a la Literatura Española I", 3, "Course description for ESPA3211", "ESPA", "admin", NOW()),
("ESPA3212", "Introducción a la Literatura Española II", 3, "Course description for ESPA3212", "ESPA", "admin", NOW()),
("ESPA3305", "Cine y Literatura", 3, "Course description for ESPA3305", "ESPA", "admin", NOW()),
("ESPA4267", "Literatura Puertorriqueña Compendio", 3, "Course description for ESPA4267", "ESPA", "admin", NOW()),
("SICI3028", "Programación Aplicada", 3, "Este curso provee los conocimientos teóricos y destrezas prácticas para hacer uso eficiente de tres tipos principales de aplicaciones usadas frecuentemente en los negocios: Procesadores de texto, hojas de cálculo y preparación de presentaciones y otros tipos de programas.", "CCOM", "juano.lopez", NOW());

INSERT INTO prerequisites VALUES
("ESPA3007", "ESPA3102"),
("ESPA3007", "ESPA3004"),
("ESPA3007", "ESPA3112"),
("ESPA3211", "ESPA3102"),
("ESPA3211", "ESPA3004"),
("ESPA3211", "ESPA3112"),
("ESPA3212", "ESPA3102"),
("ESPA3212", "ESPA3004"),
("ESPA3212", "ESPA3112"),
("ESPA3305", "ESPA3102"),
("ESPA3305", "ESPA3004"),
("ESPA3305", "ESPA3112"),
("ESPA4267", "ESPA3102"),
("ESPA4267", "ESPA3004"),
("ESPA4267", "ESPA3112");

INSERT INTO terms VALUES
("C31", "2023-2024 First Semester", 0),
("C32", "2023-2024 Second Semester", 0),
("C33", "2023-2024 Summer", 0),
("C41", "2024-2025 First Semester", 0),
("C42", "2024-2025 Second Semester", 1);

INSERT INTO term_offering VALUES
("C41", "SICI3028"),
("C41", "ESPA3305"),
("C42", "SICI3028"),
("C42", "BIOL4055"),
("C42", "ESPA3007"),
("C42", "ESPA3211"),
("C42", "ESPA3212");