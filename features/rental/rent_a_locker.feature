#language: es
@rent_a_locker
Característica: Alquilar taquillas

  Reglas:

  - Un usuario no puede alquilar más de una taquilla a la vez
  - Un usuario sancionado no puede alquilar
  - Una taquilla ya alquilada o rota no se puede alquilar
  - Si no hay taquillas libres se incluye al usuario en una lista de espera

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
      | anna@gmail.com  | ciencias      |
    Y las siguientes sanciones de tiempo:
      | usuario         | dias_sancion  |
      | luis@gmail.com  | 7             |
    Y las siguientes taquillas:
      | código          | estado        | alquilada_a     | desde | hasta |
      | 100             | disponible    |                 |       |       |
      | 101             | alquilada     | mary@gmail.com  | -1    | +1    |
      | 102             | no disponible |                 |       |       |

  Escenario: Alquilar una taquilla específica
    Dado que "john@gmail.com" quiere alquilar una taquilla
    Cuando se le asigna la taquilla "100"
    Entonces la taquilla "100" tiene el estado "alquilada"

  Escenario: Alquilar una taquilla ocupada
    Dado que "john@gmail.com" quiere alquilar una taquilla
    Cuando se le asigna la taquilla "101"
    Entonces la taquilla "101" tiene el estado "alquilada" por "mary@gmail.com"

  Escenario: Alquilar la primera taquilla libre
    Dado que "john@gmail.com" quiere alquilar una taquilla
    Cuando se le asigna una taquilla libre
    Entonces la taquilla "100" tiene el estado "alquilada"

  Escenario: Alquilar una taquilla a un usuario sancionado
    Dado que "luis@gmail.com" quiere alquilar una taquilla
    Cuando se le intenta asignar la taquilla "100"
    Entonces la taquilla "100" continúa con el estado "disponible"

  Escenario: Alquilar una taquilla a un usuario con otra ya alquilada
    Dado que "mary@gmail.com" quiere alquilar una taquilla
    Cuando se le intenta asignar la taquilla "100"
    Entonces el usuario "mary@gmail.com" tiene 1 taquilla alquilada

  Escenario: No hay taquillas libres
    Dado que no hay taquillas libres
    Y que "john@gmail.com" quiere alquilar una taquilla
    Cuando se le asigna una taquilla libre
    Entonces el usuario "john@gmail.com" no tiene ninguna taquilla asignada

  Escenario: Alquilar una taquilla por miembros de centros bloqueados
    Dado que "anna@gmail.com" quiere alquilar una taquilla
    Cuando se le intenta asignar la taquilla "100"
    Entonces la taquilla "100" continúa con el estado "disponible"
    Y el usuario "anna@gmail.com" no tiene ninguna taquilla asignada