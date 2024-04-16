import React from "react";
import { BrowserRouter, Link , Route , Routes, NavLink} from 'react-router-dom'
import Especial from "./Especial";
import App from "./App";
import "./App.css";
import { Atras } from "./components/Atras";
import { Responder } from "./components/Responder";


const inlineStyles = {
    padding: 5,
}

const Principal = () =>{
    return (
        <BrowserRouter>
         <div className="top-bar">Digital Nest</div>
            <body >
                {/* <div>
                    <NavLink  to='/Especial' style={inlineStyles}>
                        Especial 
                    </NavLink>
                    <Link className="link" to='/App' style={inlineStyles}>
                        Rapida
                    </Link>
                    </div> */}
            </body>
            <div className="link">
            <Routes>
                    <Route path='atras' element={<Atras/>}/>
                     <Route path="/Especial" element={<Especial />} />
                     <Route path="/App" element={<App />} />
                     <Route path="Responder" element={<Responder/>}/>
             </Routes>
             </div>
        </BrowserRouter>
    )
}

export default Principal;