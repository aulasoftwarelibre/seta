#language: es
@return_a_locker
Característica: Devolver una taquilla

  Reglas:

  - Un usuario puede devolver una taquilla que le pertenezca
  - Las taquillas pueden devolverse antes del plazo de vencimiento
  - Si una taquilla no se devuelve en plazo acarrea una sanción de una semana por día de retraso
  - Si la taquilla tiene más de siete días de retraso la sanción es por todo el curso

  Antecedentes:
    Dados los siguientes centros:
      | nombre                                  | código    | activo  |
      | Escuela Politécnica Superior de Córdoba | epsc      | sí      |
      | Facultad de Ciencias                    | ciencias  | no      |
    Y los siguientes usuarios:
      | email           | centro        |
      | john@gmail.com  | epsc          |
      | luis@gmail.com  | epsc          |
      | mary@gmail.com  | epsc          |
    Y las siguientes taquillas:
      | código          | estado        | alquilada_a     | desde | hasta |
      | 100             | disponible    |                 |       |       |
      | 101             | alquilada     | mary@gmail.com  | -1    | +1    |
      | 102             | alquilada     | john@gmail.com  | -5    | -2    |
      | 103             | alquilada     | luis@gmail.com  | -15   | -8    |

    Escenario: Devolver una taquilla a tiempo
      Cuando la taquilla "101" es devuelta
      Entonces la taquilla "101" tiene el estado "disponible"
      Y el usuario "mary@gmail.com" no tiene sanciones

    Escenario: Devolver una taquilla fuera de fecha
      Cuando la taquilla "102" es devuelta
      Entonces la taquilla "102" tiene el estado "disponible"
      Y el usuario "john@gmail.com" tiene una sanción por la taquilla "102" de 14 días

    Escenario: Devolver una taquilla con más de siete días de retraso
      Cuando la taquilla "103" es devuelta
      Entonces la taquilla "103" tiene el estado "disponible"
      Y el usuario "luis@gmail.com" tiene una sanción por la taquilla "103" de todo el curso
