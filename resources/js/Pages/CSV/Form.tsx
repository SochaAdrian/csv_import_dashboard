import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useState, FormEventHandler } from 'react';
import {useForm, usePage} from "@inertiajs/react";

export default function Form() {
    const { data, setData, post, processing, errors, reset } = useForm({
        file: null,
    });


    const [success, setSuccess] = useState(false);
    const [csvPreview, setCsvPreview] = useState(''); // State for CSV preview

    const handleFileChange = (e) => {
        const file = e.target.files[0];
        setData('file', file);

        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const content = e.target.result;
                setCsvPreview(content);
            };
            reader.readAsText(file);
        } else {
            setCsvPreview('');
        }
    };

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('import.file'), {
            onFinish: () => {
                reset('file');
                setCsvPreview('');
            },
            onSuccess: () => {
                setSuccess(true);
            }
        });
    };

    return (
        <AuthenticatedLayout>
            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            {success && (
                                <div className="p-4 mb-4 text-green-800 bg-green-100 border-l-4 border-green-500">
                                    File uploaded successfully.
                                </div>
                            )}
                            <form onSubmit={submit} className="space-y-4">
                                <div>
                                    <label
                                        htmlFor="file"
                                        className="block text-sm font-medium text-gray-700"
                                    >
                                        Upload CSV File
                                    </label>
                                    <input
                                        type="file"
                                        id="file"
                                        accept=".csv"
                                        onChange={handleFileChange}
                                        className="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    />
                                    {errors.file && (
                                        <p className="mt-2 text-sm text-red-600">
                                            {errors.file}
                                        </p>
                                    )}
                                </div>

                                <div className="flex gap-4">
                                    <div className="w-1/2">
                                        <label
                                            htmlFor="preview"
                                            className="block text-sm font-medium text-gray-700"
                                        >
                                            CSV Preview
                                        </label>
                                        <textarea
                                            id="preview"
                                            value={csvPreview}
                                            readOnly
                                            rows={10}
                                            className="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        {processing ? 'Uploading...' : 'Submit'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
