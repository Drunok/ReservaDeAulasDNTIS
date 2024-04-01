import React, { useState } from "react";
import { useForm, Controller } from "react-hook-form";
import SelectWithItems from "./SelectWithItems";
import { Box, Paper, TextField } from "@mui/material";

function QuickReservationForm({ capacidades, onSubmit }) {
  const { control, handleSubmit, errors } = useForm();

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

  const [horaInicial, setHoraInicial] = useState(null);

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
          <Controller
            name="capacidad"
            control={control}
            rules={{ required: true }}
            render={({ field }) => (
              <SelectWithItems
                {...field}
                items={capacidades}
                label="Selecciona Capacidad"
              />
            )}
          />
          {errors.capacidad && <span>Este campo es requerido</span>}

          <Controller
            name="fecha"
            control={control}
            defaultValue="2022-01-01"
            rules={{ required: true }}
            render={({ field }) => (
              <TextField
                {...field}
                type="date"
                label="Fecha"
                InputLabelProps={{
                  shrink: true,
                }}
              />
            )}
          />
          {errors.fecha && <span>Este campo es requerido</span>}
          {/* Continúa con el resto de tu formulario */}
        </form>
      </Paper>
    </Box>
  );
}

export default QuickReservationForm;
