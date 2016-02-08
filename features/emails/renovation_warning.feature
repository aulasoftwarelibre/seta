#language: es
@email
Característica: Envíar correos de renovación
  Reglas:

  - Se envían días antes de que caduquen
  - Los préstamos no renovables no incluyen URL de renovación

  Antecedentes:
    Dados los siguientes centros:
      | nombre                                  | código    | activo  |
      | Escuela Politécnica Superior de Córdoba | epsc      | sí      |
    Y los siguientes usuarios:
      | email           | centro        |
      | john@gmail.com  | epsc          |
      | sara@gmail.com  | epsc          |

  Escenario: Correo de aviso con renovación
    Dadas las siguientes taquillas:
      | código          | estado        | alquilada_a     | desde | hasta | renovable |
      | 101             | alquilada     | sara@gmail.com  | -7    | +2    | sí        |
      | 102             | alquilada     | john@gmail.com  | -5    | +2    | no        |
    Cuando se envía un correo de aviso de renovación con 2 días de antelación
    Entonces 2 correos deberían haber sido enviados
    Cuando abro un correo con asunto "Aviso de fin de préstamo de la taquilla 101"
    Entonces debería ver "puedes renovar por otra semana más el préstamo" en el correo
    Cuando abro un correo con asunto "Aviso de fin de préstamo de la taquilla 102"
    Entonces debería ver "NO es posible renovar tu préstamo" en el correo

  Escenario: Correo de aviso de caducidad
    Dadas las siguientes taquillas:
      | código          | estado        | alquilada_a     | desde | hasta | renovable |
      | 101             | alquilada     | sara@gmail.com  | -7    | -1    | sí        |
      | 102             | alquilada     | john@gmail.com  | -5    | +3    | no        |
    Cuando se envía un correo de aviso de renovación con 2 días de antelación
    Entonces 1 correo deberían haber sido enviado
    Cuando abro un correo con asunto "Aviso de sanción de préstamo de la taquilla 101"
    Entonces debería ver "has sobrepasado la fecha límite" en el correo

  Escenario: Correo de aviso de suspensión
    Dadas las siguientes taquillas:
      | código          | estado        | alquilada_a     | desde | hasta | renovable |
      | 101             | alquilada     | sara@gmail.com  | -10   | -8    | sí        |
      | 102             | alquilada     | john@gmail.com  | -5    | +3    | no        |
    Cuando se envía un correo de aviso de renovación con 2 días de antelación
    Entonces 1 correos deberían haber sido enviados
    Cuando abro un correo con asunto "Aviso de suspensión de préstamo por la taquilla 101"
    Entonces debería ver "se te ha suspendido definitivamente" en el correo
