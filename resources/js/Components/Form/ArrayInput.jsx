export default function ArrayInput({ label, items, onChange, onAdd, onRemove }) {
    return (
        <div>
            <p className="text-sm font-mono text-gray-700 mb-1">{label}:</p>
            {items.map((item, index) => (
                <div key={index} className="flex items-center gap-2 mb-2">
                    <input
                        type="text"
                        value={item}
                        onChange={e => onChange(index, e.target.value)}
                        className="border border-gray-300 rounded px-2 py-1 flex-1"
                    />
                    {items.length > 1 && (
                        <button
                            type="button"
                            onClick={() => onRemove(index)}
                            className="text-red-600 text-xs hover:underline"
                        >
                            Remover
                        </button>
                    )}
                </div>
            ))}
            <button
                type="button"
                onClick={onAdd}
                className="text-blue-600 text-sm hover:underline"
            >
                + Adicionar {label.toLowerCase()}
            </button>
        </div>
    );
}
