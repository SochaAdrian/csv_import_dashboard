import Authenticated from "@/Layouts/AuthenticatedLayout";
import { usePage } from "@inertiajs/react";

export default function Logs() {
    const props = usePage().props;
    const logs = props.logs;
    console.log(logs)
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
                                    <th className="border border-gray-300 px-4 py-2 text-left">Import Id</th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">Row number</th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">Row value</th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">Error message</th>
                                    <th className="border border-gray-300 px-4 py-2 text-left">Created at</th>
                                </tr>
                                </thead>
                                <tbody>
                                {logs.map((log) => (
                                    <tr key={log.id} className="hover:bg-gray-100">
                                        <td className="border border-gray-300 px-4 py-2">{log.import_id}</td>
                                        <td className="border border-gray-300 px-4 py-2">{log.row_number}</td>
                                        <td className="border border-gray-300 px-4 py-2">{log.value}</td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {log.error_message}
                                        </td>
                                        <td className="border border-gray-300 px-4 py-2">
                                            {new Date(log.created_at).toLocaleString()}
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
