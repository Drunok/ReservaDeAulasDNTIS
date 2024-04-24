import React from "react";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export const Responder = () => {
  const handleClick = () => {
    fetch("http://localhost/respuestaSolicitudRapida.php", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        if (data.solicitudesAtendidas > 0) {
            console.log(data.solicitudesAtendidas);
            toast.success("Se han atendido "+ data.solicitudesAtendidas +" solicitudes pendientes");
          } else {
            console.log(data.solicitudesAtendidas);
            toast.error("No existen solicitudes pendientes");
          }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  };

  return (
    <div>
      <button className="btnres" onClick={handleClick}>
      Atender automáticamente
      </button>
      <ToastContainer />
    </div>
  );
};

export default Responder;