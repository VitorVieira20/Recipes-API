export default function Input({ label, value, onChange }) {
    return (
        <div className="flex flex-col text-sm">
            <label className="text-gray-700 font-mono">{label}</label>
            <input
                type="text"
                value={value}
                onChange={onChange}
                className="border border-gray-300 rounded px-2 py-1 mt-1"
            />
        </div>
    );
}