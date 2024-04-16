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
import { toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import { on } from "events";

const ClasroomSelection = ({
  open,
  handleClose,
  formData,
  clasroomItems,
  onReservaExitosa,
}) => {
  const [selected, setSelected] = useState(null);
  const [items, setItems] = useState([]);

  const itemsTmp = [
    "693 A",
    "693 B",
    "691 C",
    "691 A",
    "617 A",
    "622",
    "692 B",
  ];

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
      // Extrae el nombre y la capacidad del ambiente de "selected"
      const [ambiente, capacidadConParentesis] = selected.split(" (");
      const capacidad = parseInt(capacidadConParentesis.replace(")", ""), 10);

      // Elimina los espacios en blanco al inicio y al final de la cadena "ambiente"
      const ambienteTrimmed = ambiente.trim();

      // Crea un nuevo objeto formData que incluye el ambiente seleccionado y la capacidad
      const newFormData = { ...formData, ambiente: ambienteTrimmed, capacidad };

      console.log(JSON.stringify({ formData: newFormData }));
      fetch("http://localhost/solicitudDocentePost.php", {
        method: "POST", // o 'GET', dependiendo de tu API
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ formData: newFormData }), // envía el elemento seleccionado como JSON
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.result) {
            toast.success("Reserva realizada correctamente");
            // console.log(data.result);
            console.log("La selección es válida");
            handleClose();

            onReservaExitosa();
          } else {
            console.log(data.result);
            toast.error("Reserva no puede ser realizada");
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
