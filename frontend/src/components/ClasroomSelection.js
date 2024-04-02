import React, { useState, useEffect } from "react";
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
import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

const ClasroomSelection = ({ open, handleClose, formData, clasroomItems }) => {
  const [selected, setSelected] = useState(null);
  const [items, setItems] = useState([]);

   const itemsTmp = ["693 A", "693 B", "691 C", "691 A", "617 A", "622", "692 B"];


  const handleSelect = (item) => {
    setSelected(item);
  };

  //! Metodo para obtener los elementos del servidor
  // useEffect(() => {
  //   fetch('http://localhost/peticionGet.php')
  //     .then(response => response.json())
  //     .then(data => setItems(data));
  // }, []);

  //! Metodo para enviar la seleccion al servidor
    const handleConfirm = () => {
      if (selected) {

        fetch('http://localhost/postSolicitud.php', {
          method: 'POST', // o 'GET', dependiendo de tu API
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ selected, formData }), // envía el elemento seleccionado como JSON
        })
        .then(response => response.json())
        .then(data => {
          if (data.result) {
            toast.success('Reserva realizada correctamente');
            // console.log(data.result);
            console.log("La selección es válida");
            handleClose();
          } else {
            console.log(data.result);
            console.log("La selección no es válida");
          }
        })
        .catch((error) => {
          console.error('Error:', error);
        });
      } else {
        alert("Por favor, selecciona un elemento");
      }
    };

  // const handleConfirm = () => {
  //   if (selected) {
  //     // Simula una respuesta del servidor
  //     Promise.resolve({ result: true })
  //       .then((data) => {
  //         if (data.result) {
  //           toast.success('Reserva realizada correctamente');
  //           handleClose();
  //         } else {
  //           toast.error("La selección no es válida");

  //         }
  //       })
  //       .catch((error) => {
  //         console.error("Error:", error);
  //       });
  //   } else {
  //     alert("Por favor, selecciona un elemento");
  //   }
  // };

  return (
    <Dialog open={open} onClose={handleClose}>
      <DialogTitle>{"Cuadro de Reserva"}</DialogTitle>
      <DialogContent>
        <DialogContentText>
          <Grid container spacing={2}>
            {clasroomItems.map((item, index) => (
              <Grid item xs={12} key={index}>
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
