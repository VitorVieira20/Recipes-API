import { useForm } from "@inertiajs/react";
import axios from "axios";
import { useState } from "react";
import Input from "./Form/Input";
import ArrayInput from "./Form/ArrayInput";

export default function PutEndpoint({ route }) {
    const [params, setParams] = useState({});
    const [token, setToken] = useState("");
    const [response, setResponse] = useState(null);
    const [showResult, setShowResult] = useState(false);

    // Inicialização dos dados do formulário conforme os campos opcionais do UpdateRecipeRequest
    const { data, setData } = useForm({
        name: "",
        image: "",
        description: "",
        ingredients: [""],
        instructions: [""],
        category_id: ""
    });

    const handleParamChange = (key, value) => {
        setParams((prev) => ({ ...prev, [key]: value }));
    };

    const handleArrayChange = (field, index, value) => {
        const updated = [...data[field]];
        updated[index] = value;
        setData(field, updated);
    };

    const addArrayItem = (field) => {
        setData(field, [...data[field], ""]);
    };

    const removeArrayItem = (field, index) => {
        const updated = data[field].filter((_, i) => i !== index);
        setData(field, updated);
    };

    const resolvePathWithParams = () => {
        let resolvedPath = route.path;
        if (route.params?.length) {
            route.params.forEach((param) => {
                const value = params[param.name] || "";
                resolvedPath = resolvedPath.replace(`{${param.name}}`, value);
            });
        }
        return resolvedPath;
    };

    // Filtra os campos do body para enviar apenas os que têm valor (só os que o user preencheu)
    const getFilteredData = () => {
        const filtered = {};

        Object.entries(data).forEach(([key, value]) => {
            if (Array.isArray(value)) {
                // Se array tem ao menos um item não vazio, inclui
                if (value.length && value.some((v) => v.trim() !== "")) {
                    filtered[key] = value.filter((v) => v.trim() !== "");
                }
            } else if (typeof value === "string" && value.trim() !== "") {
                filtered[key] = value;
            }
        });

        return filtered;
    };

    const callApi = async () => {
        const resolvedPath = resolvePathWithParams();

        axios
            .put(resolvedPath, getFilteredData(), {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            })
            .then((response) => {
                setResponse(response.data);
                setShowResult(true);
            })
            .catch((error) => {
                setResponse(error.response?.data ?? { error: "Erro desconhecido" });
                setShowResult(true);
            });
    };

    const clearResponse = () => {
        setResponse(null);
        setShowResult(false);
    };

    return (
        <div className="border border-gray-300 rounded-lg p-5 shadow-sm bg-white mb-8">
            <div className="flex items-center justify-between mb-3">
                <span className="text-sm px-2 py-1 rounded font-semibold bg-yellow-100 text-yellow-800">
                    PUT
                </span>
                {route.auth && (
                    <input
                        type="text"
                        placeholder="Bearer Token"
                        value={token}
                        onChange={(e) => setToken(e.target.value)}
                        className="border border-gray-300 rounded px-2 py-1 text-sm w-72"
                    />
                )}
            </div>

            <p className="text-sm text-gray-600 font-mono">{route.path}</p>
            <p className="text-gray-800 mt-1 mb-4">{route.description}</p>

            {route.params?.length > 0 && (
                <div className="mb-4 space-y-2">
                    <p className="font-semibold text-sm">Parâmetros na URL:</p>
                    {route.params.map((param, i) => (
                        <div key={i} className="flex flex-col text-sm">
                            <label className="text-gray-700 font-mono">
                                {param.name} ({param.type}){" "}
                                {param.required && <span className="text-red-600">*</span>}
                            </label>
                            <input
                                type="text"
                                value={params[param.name] || ""}
                                onChange={(e) => handleParamChange(param.name, e.target.value)}
                                placeholder={param.description}
                                className="border border-gray-300 rounded px-2 py-1 mt-1"
                            />
                        </div>
                    ))}
                </div>
            )}

            <div className="space-y-3">
                <p className="font-semibold text-sm">Body:</p>

                {/* Campos do body */}
                <Input
                    label="Nome"
                    value={data.name}
                    onChange={(e) => setData("name", e.target.value)}
                    placeholder="Máximo 255 caracteres"
                />
                <Input
                    label="Imagem (URL)"
                    value={data.image}
                    onChange={(e) => setData("image", e.target.value)}
                    placeholder="URL válida, máximo 2048 caracteres"
                />
                <Input
                    label="Descrição"
                    value={data.description}
                    onChange={(e) => setData("description", e.target.value)}
                    placeholder="Opcional"
                />
                <Input
                    label="Categoria ID"
                    value={data.category_id}
                    onChange={(e) => setData("category_id", e.target.value)}
                    placeholder="ID válido da categoria"
                />

                <ArrayInput
                    label="Ingredientes"
                    items={data.ingredients}
                    onChange={(i, v) => handleArrayChange("ingredients", i, v)}
                    onAdd={() => addArrayItem("ingredients")}
                    onRemove={(i) => removeArrayItem("ingredients", i)}
                />

                <ArrayInput
                    label="Instruções"
                    items={data.instructions}
                    onChange={(i, v) => handleArrayChange("instructions", i, v)}
                    onAdd={() => addArrayItem("instructions")}
                    onRemove={(i) => removeArrayItem("instructions", i)}
                />
            </div>

            <div className="mt-5 flex gap-2 flex-wrap">
                <button
                    onClick={callApi}
                    className="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700"
                >
                    Enviar
                </button>

                {response && (
                    <>
                        <button
                            onClick={clearResponse}
                            className="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                        >
                            Limpar
                        </button>
                        <button
                            onClick={() => setShowResult(!showResult)}
                            className="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600"
                        >
                            {showResult ? "Fechar" : "Ver resposta"}
                        </button>
                    </>
                )}
            </div>

            {showResult && response && (
                <pre className="mt-4 bg-gray-100 p-3 rounded text-sm overflow-x-auto max-h-80">
                    {JSON.stringify(response, null, 2)}
                </pre>
            )}
        </div>
    );
}
