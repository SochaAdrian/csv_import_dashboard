import Authenticated from "@/Layouts/AuthenticatedLayout";
import { usePage } from "@inertiajs/react";

export default function Dashboard() {
    const props = usePage().props;
    const imports = props.imports;
    return (
        <Authenticated>
            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <h1 className="text-xl font-bold mb-4">Import States</h1>
                            <table className="table-auto border-collapse border border-gray-300 w-full text-sm">
                                <thead>
                                <tr className="bg-gray-200">
                                    <th className="border border-gray-300 px-4 py-2 text-left">ID</th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">Created At</th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">Status</th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">Error Message (Internals)</th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">Users Imported</th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">Errors in rows</th>
                                </tr>
                                </thead>
                                <tbody>
                                {imports.map((importData) => (
                                    <tr key={importData.id} className="hover:bg-gray-100">
                                        <td className="border border-gray-300 px-4 py-2">{importData.id}</td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {new Date(importData.created_at).toLocaleString()}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2">{importData.status}</td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {importData.error_message || "No errors"}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {importData.users.length}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {importData.logs.length}
                                        </td>
                                    </tr>
                                ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </Authenticated>
    );
}
