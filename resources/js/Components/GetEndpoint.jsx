import { useState } from "react";
import { X, Eye, EyeOff, Trash2, Play } from "lucide-react";

export default function GetEndpoint({ route }) {
    const [data, setData] = useState(null);
    const [params, setParams] = useState({});
    const [queries, setQueries] = useState({});
    const [showResult, setShowResult] = useState(false);

    const handleParamChange = (key, value) => {
        setParams(prev => ({ ...prev, [key]: value }));
    };

    const handleQueryChange = (key, value) => {
        setQueries(prev => ({ ...prev, [key]: value }));
    };

    const buildUrl = () => {
        let url = route.path.replace(/\{(\w+)\}/g, (_, key) => params[key] || '');
        if (route.query?.length) {
            const queryString = route.query
                .map(({ name }) => `${name}=${encodeURIComponent(queries[name] || '')}`)
                .join('&');
            url += `?${queryString}`;
        }
        return url;
    };

    const callApi = async () => {
        const path = buildUrl();
        try {
            const response = await fetch(path);
            const json = await response.json();
            setData(json);
            setShowResult(true);
        } catch (error) {
            setData({ error: 'Erro ao chamar a API' });
            setShowResult(true);
        }
    };

    const clearResult = () => {
        setData(null);
        setShowResult(false);
    };

    return (
        <div className="border border-gray-200 rounded-2xl p-6 shadow-md bg-white mb-8 space-y-4">
            <div className="flex items-center justify-between">
                <span className="text-xs px-3 py-1 rounded-full font-semibold bg-blue-100 text-blue-800">
                    GET
                </span>
                <code className="text-gray-500 text-xs">{route.path}</code>
            </div>

            <p className="text-sm text-gray-800">{route.description}</p>

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
                                    className="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                />
                            </div>
                        ))}
                    </div>
                </div>
            )}

            {/* Query Section */}
            {route.query?.length > 0 && (
                <div className="bg-gray-50 p-4 rounded-xl border">
                    <p className="font-semibold text-sm mb-2">🔍 Query strings</p>
                    <div className="grid gap-3">
                        {route.query.map((query, i) => (
                            <div key={i} className="text-sm">
                                <label className="block font-mono text-gray-700">
                                    {query.name} ({query.type}) {query.required && <span className="text-red-600">*</span>}
                                </label>
                                <input
                                    type="text"
                                    placeholder={query.description}
                                    value={queries[query.name] || ''}
                                    onChange={(e) => handleQueryChange(query.name, e.target.value)}
                                    className="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
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
                    className="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                >
                    <Play size={16} /> Testar API
                </button>

                {data && (
                    <>
                        <button
                            onClick={clearResult}
                            className="flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition"
                        >
                            <Trash2 size={16} /> Limpar
                        </button>

                        <button
                            onClick={() => setShowResult(!showResult)}
                            className="flex items-center gap-2 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition"
                        >
                            {showResult ? <EyeOff size={16} /> : <Eye size={16} />}
                            {showResult ? 'Fechar' : 'Abrir'}
                        </button>
                    </>
                )}
            </div>

            {/* Result Viewer */}
            {data && showResult && (
                <div className="mt-4">
                    <p className="text-sm font-semibold text-gray-700 mb-1">🧾 Resposta da API:</p>
                    <pre className="bg-gray-100 max-h-64 overflow-auto p-4 rounded-lg text-xs text-gray-800 whitespace-pre-wrap">
                        {JSON.stringify(data, null, 2)}
                    </pre>
                </div>
            )}
        </div>
    );
}
