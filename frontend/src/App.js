import React from "react"; 

import IMG from "../src/campus.jpg"

function App(){
    return(
        
        <body >
            <div className="top-bar">Digital Nest</div>
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
                        <a href='/Rapida'>
                        <div className="solicitudimg">
                            <img src={IMG} alt="campus2.jpg"></img>
                        </div>
                        </a>
                        <div className="buttom">
                            <div>
                            <a href='/Rapida' className="btn">Solicitud Rapida</a>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </body>
    )
}
export default App;