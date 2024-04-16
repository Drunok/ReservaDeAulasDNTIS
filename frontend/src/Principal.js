import React from "react";
import { BrowserRouter, Route , Routes ,Switch} from 'react-router-dom'
import Especial from "./Especial";
import App from "./App";
import Responder from "./Responder";

import IMG from "../src/campus.jpg"



const Principal = () =>{
    return (
        <BrowserRouter>
             
         <div className="top-bar">Digital Nest</div>
            <body >
                <div className="solicitud">
                    <div className="solicitudes">
                        <a href='/Especial'>
                        <div className="solicitudimg">
                            <img src={IMG} alt=""/>
                        </div>
                        </a>
                        <div className="buttom">
                            <div>
                            <a href='/Especial' className="btn">Solicitud Especial</a>
                            </div>
                        </div>
                        
                    </div>
                    <div className="solicitudes">
                        <a href='/App'>
                        <div className="solicitudimg">
                            <img src={IMG} alt="campus2.jpg"></img>
                        </div>
                        </a>
                        <div className="buttom">
                            <div>
                            <a href='/App' className="btn">Solicitud Rapida</a>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </body>
            <div >
                
            <Routes>
                    
                     <Route path="/Especial" element={<Especial />}/>
                     <Route path="/App" element={<App />}  />
                     
             </Routes>
            
             <Routes>
                    <Route path="/Responder" element={<Responder/>}/>
            </Routes>
             
             </div>
        </BrowserRouter>
    )
}

export default Principal;