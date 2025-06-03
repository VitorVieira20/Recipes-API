import { useState } from "react";
import { Trash2, Eye, EyeOff, Play } from "lucide-react";
import axios from "axios";

export default function DeleteEndpoint({ route }) {
    const [params, setParams] = useState({});
    const [token, setToken] = useState("");
    const [response, setResponse] = useState(null);
    const [showResult, setShowResult] = useState(false);

    const handleParamChange = (key, value) => {
        setParams(prev => ({ ...prev, [key]: value }));
    };

    const resolvePath = () => {
        return route.path.replace(/\{(\w+)\}/g, (_, key) => params[key] || '');
    };

    const callApi = async () => {
        setResponse(null);
        setShowResult(false);

        try {
            const res = await axios.delete(resolvePath(), {
                headers: {
                    ...(route.auth && token ? { Authorization: `Bearer ${token}` } : {})
                }
            });
            setResponse(res.data ?? { success: true });
        } catch (err) {
            setResponse(err.response?.data ?? { error: "Erro desconhecido" });
        } finally {
            setShowResult(true);
        }
    };

    const clearResponse = () => {
        setResponse(null);
        setShowResult(false);
    };

    return (
        <div className="border border-gray-200 rounded-2xl p-6 shadow-md bg-white mb-8 space-y-4">
            <div className="flex items-center justify-between">
                <span className="text-xs px-3 py-1 rounded-full font-semibold bg-red-100 text-red-800">
                    DELETE
                </span>
                <code className="text-gray-500 text-xs">{route.path}</code>
            </div>

            <p className="text-sm text-gray-800">{route.description}</p>

            {/* Auth token */}
            {route.auth && (
                <div>
                    <label className="block text-sm font-mono text-gray-700 mb-1">
                        Bearer Token
                    </label>
                    <input
                        type="text"
                        value={token}
                        onChange={(e) => setToken(e.target.value)}
                        placeholder="Token de autenticação"
                        className="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-400"
                    />
                </div>
            )}

            {/* Params Section */}
            {route.params?.length > 0 && (
                <div className="bg-gray-50 p-4 rounded-xl border">
                    <p className="font-semibold text-sm mb-2">🧩 Parâmetros</p>
                    <div className="grid gap-3">
                        {route.params.map((param, i) => (
                            <div key={i} className="text-sm">
                                <label className="block font-mono text-gray-700">
                                    {param.name} ({param.type}) {param.required && <span className="text-red-600">*</span>}
                                </label>
                                <input
                                    type="text"
                                    placeholder={param.description}
                                    value={params[param.name] || ''}
                                    onChange={(e) => handleParamChange(param.name, e.target.value)}
                                    className="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-400"
                                />
                            </div>
                        ))}
                    </div>
                </div>
            )}

            {/* Action buttons */}
            <div className="flex flex-wrap gap-3 mt-2">
                <button
                    onClick={callApi}
                    disabled={route.params?.some(p => p.required && !params[p.name])}
                    className="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition disabled:opacity-50 cursor-pointer"
                >
                    <Play size={16} /> Enviar DELETE
                </button>

                {response && (
                    <>
                        <button
                            onClick={clearResponse}
                            className="flex items-center gap-2 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition cursor-pointer"
                        >
                            <Trash2 size={16} /> Limpar
                        </button>

                        <button
                            onClick={() => setShowResult(!showResult)}
                            className="flex items-center gap-2 px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition cursor-pointer"
                        >
                            {showResult ? <EyeOff size={16} /> : <Eye size={16} />}
                            {showResult ? "Fechar" : "Abrir"}
                        </button>
                    </>
                )}
            </div>

            {/* Result Viewer */}
            {response && showResult && (
                <div className="mt-4">
                    <p className="text-sm font-semibold text-gray-700 mb-1">🧾 Resposta da API:</p>
                    <pre className="bg-gray-100 max-h-64 overflow-auto p-4 rounded-lg text-xs text-gray-800 whitespace-pre-wrap">
                        {JSON.stringify(response, null, 2)}
                    </pre>
                </div>
            )}
        </div>
    );
}
