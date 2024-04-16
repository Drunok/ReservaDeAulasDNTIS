import React from "react";
import { Routes, BrowserRouter , Route} from "react-router-dom"

import {Responder} from "./Responder" 

export const paginas = () =>{
return(
    <BrowserRouter>
        <div>
            <Routes>
                    <Route path="/Responder" element={<Responder/>}/>
            </Routes>
        </div>
    </BrowserRouter>
)

}
export default paginas;