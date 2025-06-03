import { useState } from "react";
import { Trash2, Eye, EyeOff, Play } from "lucide-react";

export default function PutEndpoint({ route }) {
    const [params, setParams] = useState({});
    const [token, setToken] = useState("");
    const [response, setResponse] = useState(null);
    const [showResult, setShowResult] = useState(false);

    const [body, setBody] = useState(() => {
        const initialBody = {};
        if (route.bodyParams) {
            route.bodyParams.forEach((param) => {
                initialBody[param.name] = param.type === "array" ? [""] : "";
            });
        }
        return initialBody;
    });

    const handleParamChange = (key, value) => {
        setParams((prev) => ({ ...prev, [key]: value }));
    };

    const handleBodyChange = (key, value) => {
        setBody((prev) => ({ ...prev, [key]: value }));
    };

    const handleArrayChange = (key, index, value) => {
        const arr = [...(body[key] || [])];
        arr[index] = value;
        setBody((prev) => ({ ...prev, [key]: arr }));
    };

    const addArrayItem = (key) => {
        const arr = [...(body[key] || [])];
        arr.push("");
        setBody((prev) => ({ ...prev, [key]: arr }));
    };

    const removeArrayItem = (key, index) => {
        const arr = [...(body[key] || [])];
        arr.splice(index, 1);
        setBody((prev) => ({ ...prev, [key]: arr }));
    };

    const resolvePath = () => {
        return route.path.replace(/\{(\w+)\}/g, (_, key) => params[key] || "");
    };

    const cleanBody = (obj) => {
        const cleaned = {};
        for (const key in obj) {
            const value = obj[key];

            if (Array.isArray(value)) {
                const filtered = value.filter((item) => item?.trim?.() !== "");
                if (filtered.length > 0) {
                    cleaned[key] = filtered;
                }
            } else if (
                (typeof value === "string" && value.trim() !== "") ||
                typeof value === "number" ||
                typeof value === "boolean"
            ) {
                cleaned[key] = value;
            }
        }
        return cleaned;
    };

    const callApi = async () => {
        setResponse(null);
        setShowResult(false);
        try {
            const res = await axios.put(resolvePath(), cleanBody(body), {
                headers: {
                    ...(route.auth && token ? { Authorization: `Bearer ${token}` } : {}),
                },
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

    const isDisabled =
        route.params?.some((p) => p.required && !params[p.name]) ||
        route.bodyParams?.some((p) =>
            p.required
                ? p.type === "array"
                    ? !body[p.name]?.length || body[p.name].some((i) => !i)
                    : !body[p.name]
                : false
        );

    return (
        <div className="border border-gray-300 rounded-3xl p-6 shadow-lg bg-white mb-8 space-y-6 max-w-3xl mx-auto">
            {/* Header */}
            <div className="flex items-center justify-between">
                <span className="text-xs font-semibold bg-yellow-200 text-yellow-900 rounded-full px-3 py-1 tracking-wide">
                    PUT
                </span>
                <code className="text-gray-600 text-sm font-mono select-all">{route.path}</code>
            </div>

            {/* Description */}
            <p className="text-gray-800 text-base leading-relaxed">{route.description}</p>

            {/* Auth Token */}
            {route.auth && (
                <div>
                    <label className="block text-sm font-mono text-gray-700 mb-2">Bearer Token</label>
                    <input
                        type="text"
                        value={token}
                        onChange={(e) => setToken(e.target.value)}
                        placeholder="Token de autenticação"
                        className="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition"
                    />
                </div>
            )}

            {/* URL Params */}
            {route.params?.length > 0 && (
                <section className="bg-gray-50 border border-gray-200 rounded-xl p-5">
                    <p className="font-semibold text-sm mb-3">🧩 Parâmetros na URL</p>
                    <div className="grid gap-4">
                        {route.params.map((param, i) => (
                            <div key={i} className="text-sm">
                                <label className="block font-mono text-gray-700 mb-1">
                                    {param.name} ({param.type}){" "}
                                    {param.required && <span className="text-red-600">*</span>}
                                </label>
                                <input
                                    type="text"
                                    placeholder={param.description}
                                    value={params[param.name] || ""}
                                    onChange={(e) => handleParamChange(param.name, e.target.value)}
                                    className="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition"
                                />
                            </div>
                        ))}
                    </div>
                </section>
            )}

            {/* Body Params */}
            {route.bodyParams?.length > 0 && (
                <section className="bg-gray-50 border border-gray-200 rounded-xl p-5 space-y-5">
                    <p className="font-semibold text-sm mb-3">📦 Body</p>
                    {route.bodyParams.map((param, i) => (
                        <div key={i} className="text-sm">
                            <label className="block font-mono text-gray-700 mb-2">
                                {param.name} ({param.type}) {param.required && <span className="text-red-600">*</span>}
                            </label>
                            {param.type === "array" ? (
                                <>
                                    {(body[param.name] || []).map((item, idx) => (
                                        <div key={idx} className="flex gap-2 mb-2 items-center">
                                            <input
                                                type="text"
                                                value={item}
                                                onChange={(e) => handleArrayChange(param.name, idx, e.target.value)}
                                                placeholder={param.description}
                                                className="flex-grow border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition"
                                            />
                                            <button
                                                onClick={() => removeArrayItem(param.name, idx)}
                                                type="button"
                                                className="text-white bg-red-600 rounded-lg px-3 py-1 hover:bg-red-700 transition"
                                                aria-label={`Remover item ${idx + 1}`}
                                            >
                                                &times;
                                            </button>
                                        </div>
                                    ))}
                                    <button
                                        onClick={() => addArrayItem(param.name)}
                                        type="button"
                                        className="text-white bg-yellow-500 rounded-lg px-4 py-2 hover:bg-yellow-600 transition text-sm"
                                    >
                                        + Adicionar item
                                    </button>
                                </>
                            ) : (
                                <input
                                    type="text"
                                    value={body[param.name] || ""}
                                    onChange={(e) => handleBodyChange(param.name, e.target.value)}
                                    placeholder={param.description}
                                    className="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition"
                                />
                            )}
                        </div>
                    ))}
                </section>
            )}

            {/* Action Buttons */}
            <div className="flex flex-wrap gap-3 mt-1">
                <button
                    onClick={callApi}
                    disabled={isDisabled}
                    className={`flex items-center gap-2 px-5 py-3 rounded-xl font-semibold text-white transition ${isDisabled
                        ? "bg-yellow-400 cursor-not-allowed"
                        : "bg-yellow-500 hover:bg-yellow-600"
                        } cursor-pointer`}
                >
                    <Play size={18} /> Enviar PUT
                </button>

                {response && (
                    <>
                        <button
                            onClick={clearResponse}
                            className="flex items-center gap-2 px-5 py-3 bg-gray-500 rounded-xl text-white hover:bg-gray-600 transition cursor-pointer"
                        >
                            <Trash2 size={18} /> Limpar
                        </button>

                        <button
                            onClick={() => setShowResult(!showResult)}
                            className="flex items-center gap-2 px-5 py-3 bg-gray-700 rounded-xl text-white hover:bg-gray-800 transition cursor-pointer"
                        >
                            {showResult ? <EyeOff size={18} /> : <Eye size={18} />}
                            {showResult ? "Fechar" : "Abrir"}
                        </button>
                    </>
                )}
            </div>

            {/* Response Viewer */}
            {response && showResult && (
                <section className="mt-6">
                    <p className="text-gray-700 font-semibold mb-2">🧾 Resposta da API:</p>
                    <pre className="bg-gray-100 rounded-xl p-4 max-h-64 overflow-auto text-xs font-mono text-gray-800 whitespace-pre-wrap">
                        {JSON.stringify(response, null, 2)}
                    </pre>
                </section>
            )}
        </div>
    );
}
