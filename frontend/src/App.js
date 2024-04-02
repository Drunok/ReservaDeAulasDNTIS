import React, { useState, useEffect } from "react";
import { Button, Box, Paper } from "@mui/material";
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

  const horasIniciales = horas.slice(0, - 1);

  const docentesList = [
    "Leticia Blanco Coca",
    "Vladimir Abel Costas Jauregui",
    "Carla Salazar Serrudo",
  ];

  const materiasPorDocente = {
    "Carla Salazar Serrudo": [
      "Introducion a la programacion",
      "Sistemas de informacion I",
    ],

    "Leticia Blanco Coca": [
      "Algoritmos Avanzados",
      "Introducion a la programacion",
    ],
    "Vladimir Abel Costas Jauregui": [
      "Introducion a la programacion",
      "Programacion Web",
    ],
  };

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

  const onSubmit = async (data) => {
    try {
      const response = await fetch("http://localhost/test.php", {
        method: "POST",
        body: JSON.stringify(data),
        headers: {
          "Content-Type": "application/json",
        },
      });

      const result = await response.json();

      if (result != null) {
        // alert("Los datos del formulario son válidos");
        setFormData(data);

        // Obtén los nombres de los ambientes y sus capacidades
        const responseAulas = await fetch("http://localhost/obtenerAulas.php", {
          method: "POST",
          body: JSON.stringify(data),
          headers: {
            "Content-Type": "application/json",
          },
        });

        const resultAulas = await responseAulas.json();
        if (resultAulas && resultAulas.aulas) {
          const items = resultAulas.aulas.map(
            (aula) => `${aula.nombreambiente}  (${aula.capacidad})`
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
      <Box
        display="flex"
        flexDirection="column"
        justifyContent="center"
        alignItems="center"
        minHeight="calc(100vh - 60px)"
      >
        <Paper className="reservation-box">
          <h2>Solicitud rápida</h2>
          <form key={formKey} onSubmit={handleSubmit(onSubmit)}>
            <SelectWithItems
              {...register("capacidad", { required: true })}
              items={capacidades}
              label="Selecciona Capacidad *"
            />
            {errors.capacidad && (
              <Typography color="error">Este campo es requerido</Typography>
            )}

            <FechaField
              control={control}
              minDate={minDate}
              maxDate="2024-07-06"
            />
            {errors.fecha && (
              <Typography color="error">Este campo es requerido</Typography>
            )}

            <SelectWithItems
              {...register("hora", { required: true })}
              items={horasIniciales}
              label="Selecciona Hora *"
              onChange={(e) => setHoraInicial(e.target.value)}
            />
            {errors.hora && (
              <Typography color="error">Este campo es requerido</Typography>
            )}

            <React.Fragment>
              <SelectWithItems
                {...register("horaFinal", { required: true })}
                items={horasFinales}
                label="Selecciona Hora Final *"
              />
              {errors.horaFinal && (
                <Typography color="error">Este campo es requerido</Typography>
              )}
            </React.Fragment>

            <SelectWithItems
              {...register("docente", { required: true })}
              items={docentesList}
              label="Selecciona Docente *"
              onChange={(e) => setSelectedDocente(e.target.value)}
            />
            {errors.docente && (
              <Typography color="error">Este campo es requerido</Typography>
            )}

            <SelectWithItems
              {...register("materia", { required: true })}
              items={materias}
              label="Selecciona Materia *"
            />
            {errors.materia && (
              <Typography color="error">Este campo es requerido</Typography>
            )}

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
        // ...
      </div>
    </div>
  );
}

export default App;
