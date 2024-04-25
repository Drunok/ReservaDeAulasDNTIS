import React from "react";
import { BrowserRouter as Router, Route , Routes} from 'react-router-dom'
import Especial from "./Especial";
import Rapida from "./Rapida";
import Responder from "./Responder";
import App from "./App"



const Principal = () =>{
    return (
        <Router>
            <div >
            
            <Routes>
                    <Route exact path ="/" element={<App/>}/>
                    <Route exact path="/Rapida" element={<Rapida />}  />
                    <Route exact path="/Especial" element={<Especial/>}  />
            </Routes>
            
            <Routes>
                   <Route exact path="/Responder" element={<Responder/>}/>
           </Routes>
             </div>
        </Router>
    )
}

export default Principal;