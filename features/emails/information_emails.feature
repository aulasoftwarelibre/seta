#language: es
@email
Característica: Enviar correos informativos
  Después de determinadas acciones el usuario recibe un correo informativo para
  que le quede constancia de la acción.

  Acciones:
  - Se ha prestado una taquilla
  - Se ha renovado un prestado

  Antecedentes:
    Dados los siguientes centros:
      | nombre                                  | código    | activo  |
      | Escuela Politécnica Superior de Córdoba | epsc      | sí      |
    Y los siguientes usuarios:
      | email           | centro        |
      | sara@gmail.com  | epsc          |

  Escenario: Correo de información con préstamo
    Dadas las siguientes taquillas:
      | código          | estado        | alquilada_a     | desde | hasta | renovable |
      | 100             | disponible    |                 |       |       |           |
    Dado que "sara@gmail.com" quiere alquilar una taquilla
    Cuando se le asigna la taquilla "100"
    Entonces 1 correos deberían haber sido enviados
    Cuando abro un correo con asunto "Aviso de préstamo de la taquilla 100"
    Entonces debería ver "taquilla que acabas de tomar en préstamo" en el correo

  Escenario: Correo de información con renovación
    Dadas las siguientes taquillas:
      | código          | estado        | alquilada_a     | desde | hasta | renovable |
      | 100             | alquilada     | sara@gmail.com  | -7    | +1    | sí        |
    Cuando se quiere renovar el alquiler de la taquilla "100"
    Entonces 1 correos deberían haber sido enviados
    Cuando abro un correo con asunto "Aviso de renovación de préstamo de la taquilla 100"
    Entonces debería ver "Nuevo fin de préstamo" en el correo
