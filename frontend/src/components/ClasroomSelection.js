import React, { useState } from "react";
import {
  Button,
  Dialog,
  DialogActions,
  DialogContent,
  DialogContentText,
  DialogTitle,
  Grid,
  Paper,
} from "@mui/material";

const ClasroomSelection = ({ open, handleClose }) => {
  const [selected, setSelected] = useState(null);

  const items = ["693 A", "693 B", "691 C", "691 A", "617 A", "622", "692 B"];

  const handleSelect = (item) => {
    setSelected(item);
  };

  //! Metodo para enviar la seleccion al servidor
  //   const handleConfirm = () => {
  //     if (selected) {

  //       fetch('http://tu-servidor.com/api/ruta', {
  //         method: 'POST', // o 'GET', dependiendo de tu API
  //         headers: {
  //           'Content-Type': 'application/json',
  //         },
  //         body: JSON.stringify({ selected }), // envía el elemento seleccionado como JSON
  //       })
  //       .then(response => response.json())
  //       .then(data => {
  //         if (data.result) {
  //           console.log("La selección es válida");
  //           handleClose();
  //         } else {
  //           console.log("La selección no es válida");
  //         }
  //       })
  //       .catch((error) => {
  //         console.error('Error:', error);
  //       });
  //     } else {
  //       alert("Por favor, selecciona un elemento");
  //     }
  //   };

  const handleConfirm = () => {
    if (selected) {
      // Simula una respuesta del servidor
      Promise.resolve({ result: true })
        .then((data) => {
          if (data.result) {
            console.log("La selección es válida");
            handleClose();
          } else {
            console.log("La selección no es válida");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
        });
    } else {
      alert("Por favor, selecciona un elemento");
    }
  };

  return (
    <Dialog open={open} onClose={handleClose}>
      <DialogTitle>{"Cuadro de Reserva"}</DialogTitle>
      <DialogContent>
        <DialogContentText>
          <Grid container spacing={2}>
            {items.map((item, index) => (
              <Grid item xs={4} key={index}>
                <Paper
                  onClick={() => handleSelect(item)}
                  style={{
                    padding: "1em",
                    cursor: "pointer",
                    backgroundColor: item === selected ? "lightblue" : "white",
                  }}
                >
                  {item}
                </Paper>
              </Grid>
            ))}
          </Grid>
        </DialogContentText>
      </DialogContent>
      <DialogActions>
        <Button onClick={handleClose} color="primary">
          Salir
        </Button>
        <Button onClick={handleConfirm} color="primary" autoFocus>
          Confirmar
        </Button>
      </DialogActions>
    </Dialog>
  );
};

export default ClasroomSelection;
