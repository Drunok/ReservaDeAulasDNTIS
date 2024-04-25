import React from "react";
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Responder from "./Responder" 

export const paginas = () =>{
return(
    
    <Router>
    <Routes>
      <Route path="/Responder" element={<Responder />} />
      
    </Routes>
  </Router>
)

}
export default paginas;