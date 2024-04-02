import React, { useState } from "react";
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

  const {
    register,
    control,
    handleSubmit,
    formState: { errors },
  } = useForm();

  const [horaInicial, setHoraInicial] = useState(null);

  const [open, setOpen] = useState(false);

  const [formData, setFormData] = useState(null);

  const handleClose = () => {
    setOpen(false);
  };

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

      if (result.valid) {
        // alert("Los datos del formulario son válidos");
        setFormData(data);
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
          <h2>Cuadro de reserva</h2>
          <form onSubmit={handleSubmit(onSubmit)}>
            <SelectWithItems
              {...register("capacidad", { required: true })}
              items={capacidades}
              label="Selecciona Capacidad"
            />
            {errors.capacidad && (
              <Typography color="error">Este campo es requerido</Typography>
            )}

            <FechaField
              control={control}
              minDate={minDate}
              maxDate="2024-04-21"
            />
            {errors.fecha && (
              <Typography color="error">Este campo es requerido</Typography>
            )}

            <SelectWithItems
              {...register("hora", { required: true })}
              items={horas}
              label="Selecciona Hora"
              onChange={(e) => setHoraInicial(e.target.value)}
            />
            {errors.hora && (
              <Typography color="error">Este campo es requerido</Typography>
            )}

            <React.Fragment>
              <SelectWithItems
                {...register("horaFinal", { required: true })}
                items={horasFinales}
                label="Selecciona Hora Final"
              />
              {errors.horaFinal && (
                <Typography color="error">Este campo es requerido</Typography>
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

      <div>
        {/* ... */}
        <ClasroomSelection
          open={open}
          handleClose={handleClose}
          formData={formData}
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
