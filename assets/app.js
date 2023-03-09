import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter, Routes, Route } from "react-router-dom";

import Index from './components/Index';
import Genres from './components/GenresDropdown'

import './app.css';
import GenresDropdown from "./components/GenresDropdown";

const App = props => {
    return (
        <React.StrictMode>
          <BrowserRouter>
            <Routes>
              <Route path="/" element={<Index />} />
            </Routes>
          </BrowserRouter>
        </React.StrictMode>
    );
};

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(<App />);
