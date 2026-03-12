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
        $userMessage = strtolower($request->input('message'));

        Log::debug("User message: {$userMessage}");

        $config = Configuracion::where('clave', 'gemini_api_key')->first();
        $apiKey = $config ? $config->dato : null;

        if (!$apiKey) {
            return response()->json([
                'message' => 'El asistente no está configurado.'
            ], 500);
        }
        $productos = Product::where('name', 'like', "%{$userMessage}%")
            ->orWhere('description', 'like', "%{$userMessage}%")
            ->where('stock', '>', 0)
            ->limit(5)
            ->get(['id','name','price']);

        if ($productos->count() > 0) {

            return response()->json([
                "type" => "products",
                "products" => $productos
            ]);

        }

        $prompt = "
        Eres un asistente de ventas de una tienda escolar llamada ShopTudo.

        El cliente dijo:
        {$userMessage}

        Responde de forma amable, breve y profesional.
        Si el cliente pregunta por productos que no existen, sugiere que revise el catálogo.
        ";

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
                'message' => 'El chatbot alcanzó su límite de uso.'
            ]);
        }

        if ($response->failed()) {
            Log::error('Error Gemini: '.$response->body());
            return response()->json([
                'message' => 'Error con la IA.'
            ], 500);
        }

        $data = $response->json();

        $reply = $data['candidates'][0]['content']['parts'][0]['text']
            ?? 'No recibí respuesta de la IA.';

        return response()->json([
            "type" => "text",
            "message" => $reply
        ]);
    }
}