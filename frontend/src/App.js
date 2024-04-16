import React, { useState, useEffect } from "react";
import { Button, Box, Paper, Grid } from "@mui/material";
import "./App.css";
import SelectWithItems from "./components/SelectWithItems";
import { useForm } from "react-hook-form";
import ClasroomSelection from "./components/ClasroomSelection";
import FechaField from "./components/SelectDate";
import { Typography } from "@mui/material";
import { ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

function App() {
  const capacidades = [20, 30, 50, 100, 200, 250];
  const horas = [
    "6:45",
    "7:30",
    "8:15",
    "9:00",
    "9:45",
    "10:30",
    "11:15",
    "12:00",
    "12:45",
    "13:30",
    "14:15",
    "15:00",
    "15:45",
    "16:30",
    "17:15",
    "18:00",
    "18:45",
    "19:30",
    "20:15",
    "21:00",
    "21:45",
  ];

  const horasIniciales = horas.slice(0, -1);

  const cantDocentes = [1, 2, 3];

  const docentesList = [
    "Leticia Blanco Coca",
    // "Vladimir Abel Costas Jauregui",
    // "Carla Salazar Serrudo",
  ];

  // const materiasPorDocente = {
  //   "Carla Salazar Serrudo": [
  //     "Introducion a la programacion",
  //     "Sistemas de informacion I",
  //   ],

  //   "Leticia Blanco Coca": [
  //     "Algoritmos avanzados",
  //     "Elementos de programación y estructura de datos",
  //     "Introducción a la programación",
  //     "Taller de ingenieria de software",
  //   ],
  //   "Vladimir Abel Costas Jauregui": [
  //     "Introducion a la programacion",
  //     "Programacion Web",
  //   ],
  // };

  const materiasPorDocente = {
    "Algoritmos avanzados": ["Grupo 1"],
    "Elementos de programación y estructura de datos": ["Grupo 2", "Grupo 3"],
    "Introducción a la programación": ["Grupo 2"],
    "Taller de ingenieria de software": ["Grupo 2"],
  };

  const materiasList = [
    "Algoritmos avanzados",
    "Elementos de programación y estructura de datos",
    "Introducción a la programación",
    "Taller de ingenieria de software",
  ];

  const motivos = [
    "Primer Parcial",
    "Segundo Parcial",
    "Examen Final",
    "Segunda Instancia",
  ];

  const [docentes, setDocentes] = useState([]);
  const [selectedDocente, setSelectedDocente] = useState(null);
  const [materias, setMaterias] = useState([]);

  const obtenerMaterias = (docente) => {
    return materiasPorDocente[docente] || [];
  };

  useEffect(() => {
    if (selectedDocente) {
      const nuevasMaterias = obtenerMaterias(selectedDocente);
      setMaterias(nuevasMaterias);
    }
  }, [selectedDocente]);

  const {
    register,
    control,
    handleSubmit,
    reset,
    formState: { errors },
  } = useForm();

  const [horaInicial, setHoraInicial] = useState(null);

  const [open, setOpen] = useState(false);

  const [formData, setFormData] = useState(null);

  const [clasroomItems, setClasroomItems] = useState([]);

  const [formKey, setFormKey] = useState(0);

  const onReservaExitosa = () => {
    setFormKey(formKey + 1);
  };

  const handleClose = () => {
    setOpen(false);
  };

  useEffect(() => {
    reset();
  }, [formKey]);

  const tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  const minDate = tomorrow.toISOString().split("T")[0];

  //! Metodo para enviar el formulario al servidor
  // TODO Verificacion de los datos
  const onSubmit = async (data) => {
    try {
      const response = await fetch(
        "http://localhost/solicitudRapidaValidador.php",
        {
          method: "POST",
          body: JSON.stringify(data),
          headers: {
            "Content-Type": "application/json",
          },
        }
      );

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const result = await response.json();

      if (result != null) {
        // alert("Los datos del formulario son válidos");
        setFormData(data);
        // Obtén los nombres de los ambientes y sus capacidades
        const responseAulas = await fetch(
          "http://localhost/getAmbientesDisponibles.php",
          {
            method: "POST",
            body: JSON.stringify({formData: data}),
            headers: {
              "Content-Type": "application/json",
            },
          }
        );

        if (!responseAulas.ok) {
          throw new Error(`HTTP error! status: ${responseAulas.status}`);
        }

        const resultAulas = await responseAulas.json();
        
        if (resultAulas && resultAulas.infoAmbiente) {
          const items = resultAulas.infoAmbiente.map(
            (aula) => `${aula.nombreambiente}  (${aula.capacidadambiente})`
          );
          setClasroomItems(items);
        } else {
          console.error("No se pudo obtener las aulas del servidor");
        }

        setOpen(true);
      } else {
        alert("Los datos del formulario no son válidos");
      }
    } catch (error) {
      console.error("Error:", error);
    }
  };

  // Define las funciones para convertir horas a minutos y viceversa
  function horaAMinutos(hora) {
    const [horas, minutos] = hora.split(":").map(Number);
    return horas * 60 + minutos;
  }

  function minutosAHora(minutos) {
    const horas = Math.floor(minutos / 60);
    const mins = minutos % 60;
    return `${horas}:${mins.toString().padStart(2, "0")}`;
  }

  // Convierte la hora inicial y las horas a minutos
  let horaInicialEnMinutos = null;
  if (horaInicial !== null) {
    horaInicialEnMinutos = horaAMinutos(horaInicial);
  }
  const horasEnMinutos = horas.map(horaAMinutos);

  // Filtra las horas finales
  let horasFinalesEnMinutos = [];
  if (horaInicialEnMinutos !== null) {
    const indiceHoraInicial = horasEnMinutos.findIndex(
      (minutos) => minutos === horaInicialEnMinutos
    );
    horasFinalesEnMinutos = horasEnMinutos.slice(
      indiceHoraInicial + 1,
      indiceHoraInicial + 5
    );
  }

  // Convierte las horas finales de nuevo a strings
  const horasFinales = horasFinalesEnMinutos.map(minutosAHora);

  return (
    <div>
      <div className="top-bar">Digital Nest</div>
      <div>
        <Grid container>
          <Grid item xs={12} sm={12}>
            <Box
              display="flex"
              flexDirection="column"
              justifyContent="center"
              alignItems="center"
              minHeight="calc(100vh - 160px)"
              ml={4}
            >
              <Paper className="reservation-box">
                <h2>Solicitud rápida</h2>
                <form key={formKey} onSubmit={handleSubmit(onSubmit)}>
                  <SelectWithItems
                    {...register("docente", { required: true })}
                    items={docentesList}
                    label="Nombre del docente *"
                    // onChange={(e) => setSelectedDocente(e.target.value)}
                    // TODO algo raro pasa
                    // value={docentesList[0]}
                    // disabled
                  />
                  {/* {errors.docente && (
                    <Typography color="error">
                      Este campo es requerido
                    </Typography>
                  )} */}

                  <SelectWithItems
                    {...register("materia", { required: true })}
                    items={materiasList}
                    label="Materia *"
                    onChange={(e) => setSelectedDocente(e.target.value)}
                  />
                  {errors.materia && (
                    <Typography color="error">
                      Este campo es requerido
                    </Typography>
                  )}

                  <SelectWithItems
                    {...register("grupo", { required: true })}
                    items={materias}
                    label="Grupo *"
                  />
                  {errors.grupo && (
                    <Typography color="error">
                      Este campo es requerido
                    </Typography>
                  )}

                  <SelectWithItems
                    {...register("motivo", { required: true })}
                    items={motivos}
                    label="Motivo de la reserva*"
                  />
                  {errors.motivo && (
                    <Typography color="error">
                      Este campo es requerido
                    </Typography>
                  )}

                  <SelectWithItems
                    {...register("capacidad", { required: true })}
                    items={capacidades}
                    label="Capacidad *"
                  />
                  {errors.capacidad && (
                    <Typography color="error">
                      Este campo es requerido
                    </Typography>
                  )}

                  <FechaField
                    control={control}
                    minDate={minDate}
                    maxDate="2024-07-06"
                  />
                  {errors.fecha && (
                    <Typography color="error">
                      Este campo es requerido
                    </Typography>
                  )}

                  <SelectWithItems
                    {...register("hora", { required: true })}
                    items={horasIniciales}
                    label="Hora inicial *"
                    onChange={(e) => setHoraInicial(e.target.value)}
                  />
                  {errors.hora && (
                    <Typography color="error">
                      Este campo es requerido
                    </Typography>
                  )}

                  <React.Fragment>
                    <SelectWithItems
                      {...register("horaFinal", { required: true })}
                      items={horasFinales}
                      label="Hora final *"
                    />
                    {errors.horaFinal && (
                      <Typography color="error">
                        Este campo es requerido
                      </Typography>
                    )}
                  </React.Fragment>

                  <Button
                    type="submit"
                    variant="contained"
                    color="primary"
                    margin="normal"
                  >
                    Confirmar
                  </Button>
                </form>
              </Paper>
            </Box>
          </Grid>
        </Grid>
        <div>
          {/* ... */}
          <ClasroomSelection
            open={open}
            handleClose={handleClose}
            formData={formData}
            clasroomItems={clasroomItems}
            onReservaExitosa={onReservaExitosa}
          />
        </div>
        <div className="App">
          <ToastContainer position="top-right" />
        </div>
      </div>
    </div>
  );
}

export default App;
