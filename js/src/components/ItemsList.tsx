import Item from "./Item";
import {Todo} from "./Item";

// Defining props to pass variables between components.
interface Props {
    todos: Todo[]
    toggleTodo: (id: string, title: string, checked: boolean) => void
    deleteTodo: (id: string) => void
}

// Defining a list of items.
export default function ItemsList({todos, deleteTodo, toggleTodo}: Props) {
    // Method "map" will iterate for each item from todos array.
    return (
        <ul>
            {todos.map(todo => {
                // The key 'key' is the unique identifier for items that are repeated.
                // We are writing it here and not inside "Item" component because the component itself is repeated.
                return <Item todo={todo}
                             deleteTodo={deleteTodo}
                             toggleTodo={toggleTodo}
                             key={todo.id}/>
            })}
        </ul>
    )

}