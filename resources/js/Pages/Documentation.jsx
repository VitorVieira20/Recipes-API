import GetEndpoint from '../Components/GetEndpoint';
import PostEndpoint from '../Components/PostEndpoint';
import PutEndpoint from '../Components/PutEndpoint';
import DeleteEndpoint from '../Components/DeleteEndpoint';


const methodComponentMap = {
    GET: GetEndpoint,
    POST: PostEndpoint,
    PUT: PutEndpoint,
    DELETE: DeleteEndpoint,
};


export default function DocumentationPage({ recipesRoutes, categoryRoutes }) {
    const categories = {
        Recipes: recipesRoutes,
        Categories: categoryRoutes,
    };

    return (
        <div className="max-w-7xl mx-auto px-6 py-10">
            <h1 className="text-4xl font-bold text-center mb-12 text-gray-800">📘 Recipes API Documentation</h1>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-10">
                {Object.entries(categories).map(([categoryName, routes]) => (
                    <div key={categoryName}>
                        <h2 className="text-2xl font-semibold mb-6 text-center text-indigo-600">{categoryName}</h2>

                        <div className="space-y-6">
                            {routes.map((route, index) => {
                                const Component = methodComponentMap[route.method];
                                if (!Component) return null;

                                return (
                                    <div key={index}>
                                        <Component route={route} />
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}