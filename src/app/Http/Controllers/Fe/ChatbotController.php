<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Configuracion;

class ChatbotController extends Controller
{
    public function index(Request $request)
    {
      
        $mensajeFijo = "¡Hola! He encontrado estas cartucheras para ti:";
        $listaProductos = "- **ID**: 16 | **Nombre**: Cartuchera Doble | **Precio**: 4200.00\n" .
                        "- **ID**: 17 | **Nombre**: Cartuchera Estampada Infantil | **Precio**: 3800.00\n" .
                        "- **ID**: 18 | **Nombre**: Cartuchera Cilindrica Negra | **Precio**: 2900.00";

        return response()->json([
            'message' => $mensajeFijo . "\n" . $listaProductos
        ]);
    }
    public function indexs(Request $request)
    {  
        $userMessage = $request->input('message');
        Log::debug("User message: {$userMessage}");
        $config = Configuracion::where('clave', 'gemini_api_key')->first();
        $apiKey = $config ? $config->dato : null;

        if (!$apiKey) {
            return response()->json(['message' => 'El asistente no está configurado.'], 500);
        }

        $productos = Product::all(['id','name','price','description','stock'])
            ->toJson();

        $prompt = "Eres un asistente de ventas de ShopTudo. 
        Usa esta información: {$productos}. 
        El cliente pregunta: {$userMessage}. 
        FORMATO DE RESPUESTA: 
        1. Saluda amablemente.
        2. Lista los productos encontrados usando estrictamente este formato:
        - **ID**: [id_del_producto] | **Nombre**: [nombre] | **Precio**: [precio]
        3. Usa un salto de línea entre cada producto.
        4. No agregues texto innecesario al final.
        Si el cliente solo saluda o hace una pregunta genérica, no listes productos. 
        Solo responde con un saludo amable. Solo lista productos si el cliente muestra interés en comprar algo específico";

        $model = "gemini-2.5-flash-lite";

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ]
        ]);

        if ($response->status() == 429) {
            return response()->json([
                'message' => 'Se alcanzó el límite de uso del chatbot.'
            ]);
        }

        if ($response->failed()) {
            Log::error('Error Gemini: '.$response->body());
            return response()->json(['message' => 'Error con la IA.'], 500);
        }

        $data = $response->json();

        $reply = $data['candidates'][0]['content']['parts'][0]['text']
            ?? 'No recibí respuesta de la IA.';

        return response()->json(['message' => $reply]);
    }
}