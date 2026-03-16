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
        $userMessage = $request->input('message');
        
        $categorias = \App\Models\Categoria::pluck('nombre')->implode(', ');


        $promptIntencion = "El cliente dijo: '{$userMessage}'. 
        Si busca un producto, responde solo con el nombre clave. Si no busca nada o el producto no existe en esta lista: [{$categorias}], responde 'NADA'.";

        $keyword = trim(strtolower($this->llamarGemini($promptIntencion))); 
        $keyword = str_replace(['.', '"', "'"], '', $keyword);
        if ($keyword !== 'nada') {
            $productos = \App\Models\Product::where('active', 1)
                ->where('stock', '>', 0)
                ->where(function($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%")
                        ->orWhereHas('categorias', function($q) use ($keyword) {
                            $q->where('nombre', 'like', "%{$keyword}%");
                        });
                })->limit(5)->get(['id', 'name', 'price']);

            if ($productos->count() > 0) {
                return response()->json([
                    "type" => "products",
                    "message" => "¡Claro! Encontré estas opciones para ti:",
                    "products" => $productos
                ]);
            }
        }
        $promptGeneral = "Eres asistente de ShopTudo. Nuestras categorías son: [{$categorias}]. 
        El cliente dijo: '{$userMessage}'. 
        Si el cliente pregunta por algo fuera de nuestras categorías, dile amablemente que no trabajamos ese producto y menciona qué categorías sí tenemos.";
        
        $reply = $this->llamarGemini($promptGeneral);

        return response()->json(["type" => "text", "message" => $reply]);
    }

    private function llamarGemini($prompt)
    {
        $config = Configuracion::where('clave', 'gemini_api_key')->first();
        $apiKey = $config ? $config->dato : null;
        $model = "gemini-2.5-flash-lite";
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $response = Http::post($url, ["contents" => [["parts" => [["text" => $prompt]]]]]);
        
        return $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'nada';
    }
}