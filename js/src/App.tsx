import {useEffect, useState} from "react";
import NewItemForm from "./components/NewItemForm";
import ItemsList from "./components/ItemsList";
import {Todo} from "./components/Item";
import { request } from "graphql-request";
import { DELETE_TODO_ITEM, CREATE_TODO_ITEM, UPDATE_TODO_ITEM, TodoItemResponse } from './graphql/mutations';
import { GET_TODO_ITEMS, TodoItemsResponse } from './graphql/queries';

export default function App() {

    // The "ad-hoc" way to access a property on the window :D.
    // But normally it is better to describe the object.
    const SETTINGS =  (window as any).drupalSettings.reactApp;

    const [message, setMessage] = useState<string | null>(null);
    const [isLoading, setIsLoading] = useState(false);
    const [todos, setTodos] = useState<Todo[] | null>(null);
    const [error, setError] = useState<string | null>(null);
    const [total, setTotal] = useState<number | null>(null);

    // Other way to define method, function instead of const.
    // Defining function to update item.
    async function toggleTodo (id: string, title: string, completed: boolean) {
        try {
            await request(SETTINGS.graphql_url, UPDATE_TODO_ITEM, {id: id, title: title, completed: completed});
            setMessage('Item is updated');
            // Run again to rebuild list.
            fetchTodoItems();
        } catch (err: any) {
            // Show user human-readable message.
            setError('Could not update, please try again.')
            setMessage(null)
            // Print error to console.log.
            console.log(err.message)
        }
    }

    // Defining function to delete item.
    const deleteTodo = async (id: string) => {
        try {
            await request(SETTINGS.graphql_url, DELETE_TODO_ITEM, { data: id });
            setMessage('Item is deleted');
            // Run again to rebuild list.
            fetchTodoItems();
        } catch (err: any) {
            // Show user human-readable message.
            setError('Could not delete, please try again.')
            setMessage(null)
            // Print error to console.log.
            console.log(err.message)
        }
    };

    // Defining function to get all items.
    const fetchTodoItems = async () => {
        try {
            const data = await request<TodoItemsResponse>(SETTINGS.graphql_url, GET_TODO_ITEMS);
            if (data.TodoItems.total > 0) {
                setTodos(data.TodoItems.items);
                setTotal(data.TodoItems.total);
            }
            else {
                setMessage("You don't have items yet.");
                setTodos(null)
                setTotal(null);
            }
            setError(null)
        } catch (err: any) {
            // Show user human-readable message.
            setError('Error happened, please reload the page.')
            setMessage(null)
            // Print error to console.log ("Some error (((") or full error.
            console.log(err.response?.errors?.[0]?.message || err);
        } finally {
            setIsLoading(false);
        }
    };

    // Defining function to create item.
    const addTodo = async (title: string) => {
        setIsLoading(true);
        try {
            const data = await request<TodoItemResponse>(SETTINGS.graphql_url, CREATE_TODO_ITEM, { data: title});
            setMessage('Item is created: ' + data?.CreateTodoItem?.item?.title);
            // Run again to rebuild list.
            fetchTodoItems();
        } catch (err: any) {
            // Show user human-readable message.
            setError('Could not create the item, please reload the page.')
            setMessage(null)
            // Print error to console.log.
            console.log(err.message)
        } finally {
            setIsLoading(false);
        }
    };

    // Runs on every app render.
    useEffect(() => {
        setIsLoading(true);
        fetchTodoItems();
    }, []);

    // Confining all components and building final app.
    return (
        <>
            <NewItemForm onSubmit={addTodo} />
            {/* List title is a variable from Drupal settings. */}
            <h3>{SETTINGS.list_title}</h3>
            {message && <div className="some-message status">{message}</div>}
            {error && <div className="some-message error">{error}</div>}
            {isLoading && <div className="some-message loading">Loading...</div>}
            {todos && <ItemsList todos={todos} toggleTodo={toggleTodo} deleteTodo={deleteTodo} />}
            {total && <div className="some-message total">Total: {total}</div>}
        </>
    )
}