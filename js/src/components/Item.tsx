import {useState} from "react";

// Define Todo object.
export interface Todo {
    id: string;
    title: string;
    completed: boolean;
}

// Defining props to pass variables between components.
interface Props {
    todo: Todo
    toggleTodo: (id: string, title: string, checked: boolean) => void
    deleteTodo: (id: string) => void
}

export default function Item({todo, toggleTodo, deleteTodo}: Props) {
    const [deleting, setDeleting] = useState<boolean>(false);
    const [updating, setUpdating] = useState<boolean>(false);

    // Make progress of deleting visible.
    const deleteItem = async () => {
        setDeleting(true);
        await deleteTodo(todo.id);
        setDeleting(false);
    }

    // Make progress of updating visible.
    const updateItem = async (checked: boolean) => {
        setUpdating(true);
        await toggleTodo(todo.id, todo.title, checked);
        setUpdating(false);
    }

    return(
        <li className="list">
            <input
                type="checkbox"
                checked={todo.completed}
                onChange={e => updateItem(e.target.checked)}
            />
            <span className="todo-item">{todo.title}</span>
            <button
                className="button--small"
                onClick={() => deleteItem()}
            >Delete</button>
            {deleting && <span>Deleting...</span>}
            {updating && <span>Updating...</span>}
        </li>
    )
}
