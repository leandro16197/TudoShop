import React from 'react';
import { Link } from 'react-router-dom';

const ErrorPage = () => {
    return (
        <div className="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-4">
            <div className="bg-white p-8 rounded-lg shadow-md max-w-md w-full text-center">
                <div className="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg className="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h1 className="text-2xl font-bold text-gray-800 mb-2">Hubo un problema</h1>
                <p className="text-gray-600 mb-6">No pudimos procesar tu pago. Por favor, intenta nuevamente o utiliza otro medio de pago.</p>
                
                <div className="space-y-3">
                    <Link 
                        to="/checkout" 
                        className="block w-full bg-red-600 text-white font-semibold py-2 rounded-md hover:bg-red-700 transition"
                    >
                        Reintentar Pago
                    </Link>
                    <Link 
                        to="/" 
                        className="block w-full text-gray-500 hover:text-gray-700 text-sm font-medium"
                    >
                        Volver al inicio
                    </Link>
                </div>
            </div>
        </div>
    );
};

export default ErrorPage;