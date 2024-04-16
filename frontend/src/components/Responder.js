import React from "react";

export const Responder = () => {
  const handleClick = () => {
    fetch("http://localhost/tu-endpoint", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ key: "value" }), // reemplaza esto con tus datos
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        console.log(data);
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  };

  return (
    <div>
      <button className="btnres" onClick={handleClick}>
        Responder Solicitud
      </button>
    </div>
  );
};

export default Responder;